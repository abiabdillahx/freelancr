<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/register/client', 'auth.register-form', [
    'role' => 'client',
    'roleLabel' => 'Client',
    'roleTitle' => 'Buat akun client',
    'roleDescription' => 'Cari jasa, pilih freelancer, dan kelola pesanan dari satu akun.',
])->name('register.client');
Route::view('/register/freelancer', 'auth.register-form', [
    'role' => 'freelancer',
    'roleLabel' => 'Freelancer',
    'roleTitle' => 'Buat akun freelancer',
    'roleDescription' => 'Pasang jasa, terima pesanan, dan bangun portofolio kamu.',
])->name('register.freelancer');

Route::view('/jasa', 'marketplace.services')->name('services');
Route::view('/jasa/{id}', 'marketplace.service-detail')->name('services.show');

Route::view('/proyek/baru', 'marketplace.project-create')->name('projects.create');
Route::view('/freelancer', 'marketplace.freelancers')->name('freelancers');
Route::view('/freelancer/{id}', 'marketplace.freelancer-detail')->name('freelancers.show');

Route::view('/pesanan', 'marketplace.orders')->name('orders');
Route::view('/jasa-saya', 'marketplace.services-manage')->name('services.manage');
Route::view('/dashboard', 'marketplace.dashboard')->name('dashboard');