<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ShareLinkController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    // Password Reset Routes (Firebase)
    Route::get('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password', [\App\Http\Controllers\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\ForgotPasswordController::class, 'reset'])->name('password.update');
    
    // OAuth routes
    Route::get('/oauth/{provider}', [App\Http\Controllers\SocialAuthController::class, 'redirectToProvider'])->name('oauth.redirect');
    Route::get('/oauth/{provider}/callback', [App\Http\Controllers\SocialAuthController::class, 'handleProviderCallback'])->name('oauth.callback');
});

// Public Share Routes (No Auth required)
Route::get('/s/{token}', [ShareLinkController::class, 'show'])->name('shares.public');
Route::post('/s/{token}/verify', [ShareLinkController::class, 'verify'])->name('shares.verify');
Route::get('/s/{token}/download/{file}', [ShareLinkController::class, 'download'])->name('shares.download');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Settings
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::put('/settings', [UserController::class, 'updateSettings'])->name('settings.update');

    // File & Folder Management
    Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::post('/files/upload', [FileController::class, 'store'])->name('files.upload');
    Route::get('/files/download/{file}', [FileController::class, 'download'])->name('files.download');
    Route::get('/files/preview/{file}', [FileController::class, 'preview'])->name('files.preview');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
    Route::get('/trash', [FileController::class, 'trash'])->name('files.trash');

    Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
    Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');
    Route::post('/folders/{id}/restore', [FolderController::class, 'restore'])->name('folders.restore');
    Route::get('/folders/download-zip/{folder}', [FolderController::class, 'downloadZip'])->name('folders.download.zip');

    // Sharing Links Management
    Route::post('/shares', [ShareLinkController::class, 'store'])->name('shares.store');
    Route::delete('/shares/{shareLink}', [ShareLinkController::class, 'destroy'])->name('shares.revoke');
});

