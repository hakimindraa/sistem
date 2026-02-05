<?php namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';

    protected $allowedFields = ['nim','nama','angkatan','jurusan'];

    protected $useTimestamps = false;
    protected $returnType = 'array';
}
