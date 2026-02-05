<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ----------------------
// FRONTEND VIEWS (namespace default App\Controllers)
// ----------------------

// Root redirect ke dashboard
$routes->get('/', 'Dashboard::index');

// Dashboard
$routes->get('dashboard', 'Dashboard::index');

// halaman daftar / create / edit mahasiswa (views)
$routes->get('mahasiswa', 'Mahasiswa::index');
$routes->get('mahasiswa/create', 'Mahasiswa::create');
$routes->get('mahasiswa/edit/(:segment)', 'Mahasiswa::edit/$1');

// auth view (login) - pakai controller Auth di App\Controllers
// jika kamu memilih menampilkan view via controller Auth::login
$routes->get('login', 'Auth::login');
$routes->get('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout'); // optional: jika membuat method logout
$routes->get('reset-password', 'Auth::resetPassword');

// ----------------------
// API routes (namespace App\Controllers\Api)
// ----------------------
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    // public auth endpoints (API)
    $routes->post('auth/register', 'Auth::register');
    $routes->post('auth/login', 'Auth::login');
    $routes->post('auth/reset-password', 'Auth::resetPassword');

    // protected mahasiswa routes (filter jwt)
    $routes->group('', ['filter' => 'jwt'], function ($routes) {
        // logout endpoint (will revoke token server-side)
        $routes->post('auth/logout', 'Auth::logout');

        $routes->get('mahasiswa', 'Mahasiswa::index');
        $routes->post('mahasiswa', 'Mahasiswa::create');
        $routes->get('mahasiswa/(:segment)', 'Mahasiswa::show/$1');
        $routes->put('mahasiswa/(:segment)', 'Mahasiswa::update/$1');
        $routes->delete('mahasiswa/(:segment)', 'Mahasiswa::delete/$1');
    });
});
