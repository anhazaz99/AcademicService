<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\MonHocController;
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
Route::post('/sinhvien',[SinhVienController::class, 'AddSinhVien']);
Route::middleware([JwtAuthenticate::class])->group(function() {
    Route::get('/sinhvien-all',[SinhVienController::class, 'AllSinhVien']);
    Route::put('/sinhvien/{id}',[SinhVienController::class, 'UpdateSinhVien']);
    Route::delete('/sinhvien/{id}', [SinhVienController::class, 'DeleteSinhVien']);
    Route::get('/getSinhVien/{user_id}', [SinhVienController::class , 'getSinhVienByUserId']);
});

// GiaoVien CRUD routes
Route::middleware([JwtAuthenticate::class])->group(function () {
    Route::apiResource('giaoviens', GiaoVienController::class);
});

// MonHoc CRUD routes
Route::middleware([JwtAuthenticate::class])->group(function () {
    Route::get('/monhocs', [MonHocController::class, 'index']);
    Route::post('/monhocs', [MonHocController::class, 'store']);
    Route::get('/monhocs/{monhoc}', [MonHocController::class, 'show']);
    Route::put('/monhocs/{monhoc}', [MonHocController::class, 'update']);
    Route::delete('/monhocs/{monhoc}', [MonHocController::class, 'destroy']);
    
    // Các routes bổ sung cho môn học
    Route::get('/monhocs-khoa/{khoaId}', [MonHocController::class, 'getByKhoa']);
    Route::get('/monhocs-giaovien/{giaoVienId}', [MonHocController::class, 'getByGiaoVien']);
    Route::get('/monhocs-search', [MonHocController::class, 'search']);
});
    

