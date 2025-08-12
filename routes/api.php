<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SinhVienController;
use App\Http\Middleware\JwtAuthenticate;
use App\Models\SinhVien;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GiaoVienController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware([JwtAuthenticate::class])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    
});


//SinhVien
Route::middleware([JwtAuthenticate::class])->group(function() {
    Route::post('/addsinhvien',[SinhVienController::class, 'AddSinhVien']);
    Route::get('/danhsachsinhvien',[SinhVienController::class, 'AllSinhVien']);
    Route::post('/capnhatsinhvien/{id}',[SinhVienController::class, 'UpdateSinhVien']);
    Route::delete('/xoasinhvien/{id}', [SinhVienController::class, 'DeleteSinhVien']);
    Route::get('/thongtinsinhviendangnhap/{user_id}', [SinhVienController::class , 'getSinhVienByUserId']);
});


    // GiaoVien CRUD routes
    Route::apiResource('giaoviens', GiaoVienController::class);
    

