<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ProfileController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\UserController;
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
        Route::match(['get', 'post'], '/login', [LoginController::class, 'login'])->name('login'); // Login
        Route::match(['get', 'post'], 'register', [RegisterController::class, 'register'])->name('register'); // Register

        // Forget and reset password
        Route::prefix('/password')->name('password.')->group(function () {
            Route::match(['get', 'post'], '/forget', [ResetPasswordController::class, 'forget'])->name('forget');
            Route::match(['get', 'post'], '/reset/{token}', [ResetPasswordController::class, 'reset'])->name('reset');
        });
    });

    // Authenticated
    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('home'); // Dashboard

        // Auth
        Route::prefix('/auth')->name('auth.')->group(function () {
            Route::get('/logout', [LoginController::class, 'logout'])->name('logout'); // Logout

            // Profile group
            Route::prefix('/profile')->name('profile.')->group(function () {
                Route::match(['get', 'put'], '/', [ProfileController::class, 'profile'])->name('index'); // Update profile
                Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('password'); // Chane password
            });
        });

        // System
        Route::prefix('/system')->name('system.')->group(function () {
            Route::get('/info', [SystemController::class, 'systemInfo'])->name('info');
        });

        // Admin
        Route::resource('/users', UserController::class);

        /**
         * Setting module
         */
        Route::prefix('/settings')->name('settings.')->group(function () {
            Route::match(['get', 'put'], '/', [SettingController::class, 'index'])->name('index'); // General settings
            Route::match(['get', 'put'], '/email', [SettingController::class, 'email'])->name('email'); // Email setting
            Route::match(['get', 'put'], '/social-login', [SettingController::class, 'socialLogin'])->name('social_login'); // Social login
        });
    });
});
