<?php namespace App\Controllers;

class Mahasiswa extends BaseController
{
    public function index()
    {
        return view('mahasiswa/index');
    }

    public function create()
    {
        return view('mahasiswa/create');
    }

    public function edit($nim)
    {
        return view('mahasiswa/edit', ['nim' => $nim]);
    }
}
