<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// halaman utama
Route::get('/', function () {
    return view('welcome');
});

// halaman login
Route::get('/login', function () {
    return view('login');
})->name('login');

// halaman register
Route::get('/register', function () {
    return view('register');
})->name('register');

// proses register
Route::post('/register', [AuthController::class, 'register']);

// proses login
Route::post('/login', [AuthController::class, 'login']);

// dashboard setelah login
Route::get('/dashboard', function () {
    return view('dashboard');
});