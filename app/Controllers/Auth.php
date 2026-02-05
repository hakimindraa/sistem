<?php namespace App\Controllers;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function resetPassword()
    {
        return view('auth/reset_password');
    }

    public function logout()
    {
        // contoh sederhana: hapus token client (JS) lalu redirect
        echo "<script>localStorage.removeItem('token');window.location.href='/'</script>";
    }
}
