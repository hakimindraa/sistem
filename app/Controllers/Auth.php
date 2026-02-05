<?php namespace App\Controllers;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function logout()
    {
        // contoh sederhana: hapus token client (JS) lalu redirect
        echo "<script>localStorage.removeItem('token');window.location.href='/'</script>";
    }
}
