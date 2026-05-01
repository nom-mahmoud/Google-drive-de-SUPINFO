<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('folders', FolderController::class);
    Route::get('/folders/{folder}/download', [FolderController::class, 'download']);

    Route::apiResource('files', FileController::class)->except(['store', 'show']);
    Route::post('/files', [FileController::class, 'store']);
    Route::get('/files/{file}/download', [FileController::class, 'download']);
    Route::get('/files/{file}/preview', [FileController::class, 'preview']);

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index']);
    Route::apiResource('share-links', \App\Http\Controllers\ShareLinkController::class)->only(['index', 'store', 'destroy']);
});

use App\Http\Controllers\OAuthController;

Route::get('/auth/{provider}/redirect', [OAuthController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [OAuthController::class, 'callback']);

Route::post('/share-links/access/{token}', [\App\Http\Controllers\ShareLinkController::class, 'access']);
