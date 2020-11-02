<?php

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

Route::get('/', function () {
    return redirect()->route('show');
});

Route::get('login', [\App\Http\Controllers\AuthController::class, 'show'])
  ->name('show');

Route::post('login', [\App\Http\Controllers\AuthController::class, 'authForm'])
  ->name('authForm');

Route::get('user/{id}',
  [\App\Http\Controllers\AuthController::class, 'userPage'])->name('userPage');

Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])
  ->name('logout');
