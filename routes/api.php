<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\JwtAuthenticate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GiaoVienController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//Route::apiResource('giaoviens', GiaoVienController::class);
Route::middleware([JwtAuthenticate::class])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::apiResource('giaoviens', GiaoVienController::class);
});


