<?php namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MahasiswaModel;

class Mahasiswa extends ResourceController
{
    protected $modelName = MahasiswaModel::class;
    protected $format = 'json';

    // GET /api/mahasiswa
    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // POST /api/mahasiswa
    public function create()
{
    try {
        $data = $this->request->getJSON(true);

        if (!$data || !isset($data['nim'])) {
            return $this->failValidationErrors('NIM wajib diisi');
        }

        if ($this->model->find($data['nim'])) {
            return $this->fail('NIM sudah terdaftar', 400);
        }

        $ok = $this->model->insert($data);

        if ($ok === false) {
            // Ambil pesan error dari database
            $db = \Config\Database::connect();
            $err = $db->error(); // array kode & message
            return $this->failServerError('Gagal menyimpan data: ' . json_encode($err));
        }

        return $this->respondCreated($this->model->find($data['nim']));
    } catch (\Throwable $e) {
        return $this->failServerError($e->getMessage() . "\n" . $e->getTraceAsString());
    }
}


    // GET /api/mahasiswa/{nim}
    public function show($nim = null)
    {
        $m = $this->model->find($nim);
        if (!$m) return $this->failNotFound('Mahasiswa tidak ditemukan');

        return $this->respond($m);
    }

    // PUT /api/mahasiswa/{nim}
    public function update($nim = null)
    {
        $m = $this->model->find($nim);
        if (!$m) return $this->failNotFound('Mahasiswa tidak ditemukan');

        $data = $this->request->getJSON(true);
        unset($data['nim']); // cegah ganti NIM

        $this->model->update($nim, $data);

        return $this->respond($this->model->find($nim));
    }

    // DELETE /api/mahasiswa/{nim}
    public function delete($nim = null)
    {
        $m = $this->model->find($nim);
        if (!$m) return $this->failNotFound('Mahasiswa tidak ditemukan');

        $this->model->delete($nim);

        return $this->respondDeleted(['message' => 'Data berhasil dihapus']);
    }
}
