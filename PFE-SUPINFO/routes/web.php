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
    
    // OAuth routes
    Route::get('/oauth/{provider}', [App\Http\Controllers\SocialAuthController::class, 'redirectToProvider'])->name('oauth.redirect');
    Route::get('/oauth/{provider}/callback', [App\Http\Controllers\SocialAuthController::class, 'handleProviderCallback'])->name('oauth.callback');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Stub dashboard route
    Route::get('/dashboard', function () {
        return "Dashboard";
    })->name('dashboard');

    // UI Mock Routes for Week 2 (File Manager)
    Route::get('/files', function (\Illuminate\Http\Request $request) {
        $userAgent = $request->header('User-Agent');
        $isMobile = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $userAgent);
        
        if ($isMobile) {
            return view('mobile.files.index');
        }
        return view('web.files.index');
    })->name('files.index');
});
