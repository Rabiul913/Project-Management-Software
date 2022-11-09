<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
})->name('/');
Route::get('/register', function () {
    return view('registration');
})->name('register');

Route::post('/admin-login', [LoginController::class, 'adminLogin'])->name('admin-login');
Route::post('/admin-register', [RegisterController::class, 'user_register'])->name('admin-register');
Route::get('/admin-logout', [LoginController::class, 'logout'])->name('logout');