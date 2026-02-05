<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\Config\Services;

class JwtAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization') ?: $request->getServer('HTTP_AUTHORIZATION');
        if (!$authHeader) {
            return Services::response()
                ->setStatusCode(401)
                ->setJSON(['error' => 'Authorization header missing']);
        }

        if (strpos($authHeader, 'Bearer ') !== 0) {
            return Services::response()
                ->setStatusCode(401)
                ->setJSON(['error' => 'Invalid Authorization header format']);
        }

        $token = substr($authHeader, 7);
        $secret = getenv('app.jwtSecret') ?: 'change_me';

        try {
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));

            // cek revoked tokens
            $jti = $decoded->jti ?? null;
            if ($jti) {
                $db = \Config\Database::connect();
                $row = $db->table('revoked_tokens')->where('jti', $jti)->get()->getRow();
                if ($row) {
                    return Services::response()
                        ->setStatusCode(401)
                        ->setJSON(['error' => 'Token has been revoked']);
                }
            }

            // expose decoded for controllers
            $request->decoded = $decoded;
        } catch (\Throwable $e) {
            return Services::response()
                ->setStatusCode(401)
                ->setJSON(['error' => 'Token tidak valid atau kedaluwarsa', 'msg' => $e->getMessage()]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing
    }
}
