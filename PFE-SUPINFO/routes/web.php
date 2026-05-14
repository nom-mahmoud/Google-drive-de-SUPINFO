<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    // Stub OAuth routes
    Route::get('/oauth/google', function() { return "Google OAuth"; })->name('oauth.google');
    Route::get('/oauth/github', function() { return "GitHub OAuth"; })->name('oauth.github');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Stub dashboard route
    Route::get('/dashboard', function () {
        return "Dashboard";
    })->name('dashboard');

    // File & Folder Management (Week 2)
    Route::get('/files', [App\Http\Controllers\FileController::class, 'index'])->name('files.index');
    Route::post('/files/upload', [App\Http\Controllers\FileController::class, 'store'])->name('files.upload');
    Route::get('/files/download/{file}', [App\Http\Controllers\FileController::class, 'download'])->name('files.download');
    Route::delete('/files/{file}', [App\Http\Controllers\FileController::class, 'destroy'])->name('files.destroy');
    Route::get('/trash', [App\Http\Controllers\FileController::class, 'trash'])->name('files.trash');

    Route::post('/folders', [App\Http\Controllers\FolderController::class, 'store'])->name('folders.store');
    Route::delete('/folders/{folder}', [App\Http\Controllers\FolderController::class, 'destroy'])->name('folders.destroy');
    Route::post('/folders/{id}/restore', [App\Http\Controllers\FolderController::class, 'restore'])->name('folders.restore');
});
