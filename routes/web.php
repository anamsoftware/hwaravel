<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Client\DevController;
use App\Http\Controllers\Client\HomeController;
use Illuminate\Support\Facades\Route;

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

Route::name('client.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/hwa-dev/{key}', [DevController::class, 'dev'])->name('dev');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::prefix(hwa_admin_dir())->name('admin.')->group(function () {
    // Unauthenticated
    Route::prefix('/auth')->name('auth.')->group(function () {
        // Login
        Route::match(['get', 'post'], '/login', [LoginController::class, 'login'])->name('login');
        // Register
        Route::match(['get', 'post'], 'register', [RegisterController::class, 'register'])->name('register');

    });

    // Authenticated
    Route::middleware('auth:admin')->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('home');

        // Auth
        Route::prefix('/auth')->name('auth.')->group(function () {
            // Logout
            Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
        });
    });
});
