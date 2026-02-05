<?php namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\Config\Services;

class Auth extends ResourceController
{
    protected $format = 'json';
    protected $userModel;
    protected $secret;
    protected $expire;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->secret = getenv('app.jwtSecret') ?: 'change_me';
        $this->expire = (int)(getenv('app.jwtExpire') ?: 3600);
    }

    public function register()
    {
        $data = $this->request->getJSON(true);
        if (!isset($data['username']) || !isset($data['password'])) {
            return $this->failValidationErrors('username & password wajib');
        }

        if ($this->userModel->where('username', $data['username'])->first()) {
            return $this->fail('Username sudah terpakai', 400);
        }

        $save = [
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'user'
        ];

        $this->userModel->insert($save);
        return $this->respondCreated(['message' => 'User terdaftar']);
    }

    public function login()
    {
        $data = $this->request->getJSON(true);
        if (!isset($data['username']) || !isset($data['password'])) {
            return $this->failValidationErrors('username & password wajib');
        }

        $user = $this->userModel->where('username', $data['username'])->first();
        if (!$user) return $this->failNotFound('User tidak ditemukan');

        if (!password_verify($data['password'], $user['password'])) {
            return $this->fail('Password salah', 401);
        }

        $now = time();
        $jti = bin2hex(random_bytes(16)); // unique token id

        $payload = [
            'iat' => $now,
            'exp' => $now + $this->expire,
            'jti' => $jti,
            'uid' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];

        $token = JWT::encode($payload, $this->secret, 'HS256');

        return $this->respond([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->expire
        ]);
    }

    public function resetPassword()
    {
        $data = $this->request->getJSON(true);
        if (!isset($data['username']) || !isset($data['new_password'])) {
            return $this->failValidationErrors('username & new_password wajib');
        }

        $user = $this->userModel->where('username', $data['username'])->first();
        if (!$user) {
            return $this->failNotFound('User tidak ditemukan');
        }

        // Update password
        $newPasswordHash = password_hash($data['new_password'], PASSWORD_DEFAULT);
        $this->userModel->update($user['id'], ['password' => $newPasswordHash]);

        return $this->respond([
            'message' => 'Password berhasil direset',
            'username' => $data['username']
        ]);
    }

    /**
     * Logout: revoke token on server side by saving jti into revoked_tokens table.
     * This endpoint must be protected by jwt filter (so only valid tokens can call it).
     */
    public function logout()
    {
        $authHeader = $this->request->getHeaderLine('Authorization') ?: $this->request->getServer('HTTP_AUTHORIZATION');
        if (!$authHeader || strpos($authHeader, 'Bearer ') !== 0) {
            return $this->failUnauthorized('Authorization header missing');
        }

        $token = substr($authHeader, 7);

        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Throwable $e) {
            return $this->fail('Token invalid: ' . $e->getMessage(), 401);
        }

        $jti = $decoded->jti ?? null;
        $expTs = isset($decoded->exp) ? (int)$decoded->exp : (time() + $this->expire);
        $exp = date('Y-m-d H:i:s', $expTs);

        if ($jti) {
            $db = \Config\Database::connect();
            $builder = $db->table('revoked_tokens');

            // hindari duplicate insert
            $exists = $builder->where('jti', $jti)->get()->getRow();
            if (!$exists) {
                $builder->insert([
                    'jti' => $jti,
                    'expired_at' => $exp
                ]);
            }
        }

        return $this->respond(['message' => 'Logout sukses, token dibatalkan di server']);
    }
}
