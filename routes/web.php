<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\Onboarded;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');
Route::redirect('/dashboar', '/dashboard');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerForm']);
    Route::post('/register', [AuthController::class, 'register']);
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/setup', [AppController::class, 'setup']);
    Route::post('/setup', [AppController::class, 'setupSave']);

    Route::middleware(Onboarded::class)->group(function () {
        Route::get('/dashboard', [AppController::class, 'dashboard']);
        Route::get('/rooms', [AppController::class, 'rooms']);
        Route::get('/rooms/{space}/assign', [AppController::class, 'assignForm']);
        Route::post('/rooms/{space}/assign', [AppController::class, 'assign']);
        Route::post('/people/{id}/leave', [AppController::class, 'leave']);
        Route::get('/payments', [AppController::class, 'payments']);
        Route::post('/payments', [AppController::class, 'payStore']);
        Route::get('/settings', [AppController::class, 'settings']);
        Route::post('/settings', [AppController::class, 'settingsSave']);
        Route::post('/spaces', [AppController::class, 'spaceStore']);
        Route::put('/spaces/{id}', [AppController::class, 'spaceUpdate']);
        Route::delete('/spaces/{id}', [AppController::class, 'spaceDelete']);
    });
});
