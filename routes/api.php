<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SinhVienController;
use App\Http\Middleware\JwtAuthenticate;
use App\Models\SinhVien;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GiaoVienController;
use App\Http\Controllers\DiemController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['jwt'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    
    // ===== ROUTES CHUNG CHO ADMIN VÀ GIÁO VIÊN =====
    Route::middleware(['role:admin,giaovien'])->group(function(){
        // Xem danh sách điểm
        Route::get('/diems', [DiemController::class, 'index']);
        Route::get('/diems/{id}', [DiemController::class, 'show']);
        Route::get('/diems/sinhvien/{sinhVienId}', [DiemController::class, 'getBySinhVien']);
        
        // Thêm và cập nhật điểm
        Route::post('/diems', [DiemController::class, 'store']);
        Route::put('/diems/{id}', [DiemController::class, 'update']);
    });
    
    // ===== ROUTER CHỈ CHO ADMIN =====
    Route::middleware(['role:admin'])->group(function(){
        // GiaoVien CRUD admin
        Route::apiResource('giaoviens', GiaoVienController::class); 
        
        // SinhVien CRUD admin
        Route::post('/addsinhvien',[SinhVienController::class, 'AddSinhVien']);
        Route::get('/danhsachsinhvien',[SinhVienController::class, 'AllSinhVien']);
        Route::put('/capnhatsinhvien',[SinhVienController::class, 'UpdateSinhVien']);
        Route::delete('/xoasinhvien', [SinhVienController::class, 'DeleteSinhVien']);
        Route::get('/thongtinsinhviendangnhap', [SinhVienController::class , 'CurrentSinhVien']);

        // Chỉ admin mới được xóa điểm
        Route::delete('/diems/{id}', [DiemController::class, 'destroy']);
    });

    // ===== ROUTER CHỈ CHO GIÁO VIÊN =====
    Route::middleware(['role:giaovien'])->group(function(){
        Route::get('/thongtinsinhvien', [GiaoVienController::class, 'ThongTinSinhVien']);
        Route::get('/giaovien/profile', [GiaoVienController::class, 'myProfile']);
        Route::put('/giaovien/profile', [GiaoVienController::class, 'update']);
    });

    // ===== ROUTER CHỈ CHO SINH VIÊN =====
    Route::middleware(['role:sinhvien'])->group(function(){
        Route::get('/thongtinsinhvien', [SinhVienController::class, 'ThongTinSinhVien']);
        Route::get('/sinhvien/diems', [DiemController::class, 'myScores']);
    });
});
