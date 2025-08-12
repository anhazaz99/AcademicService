<?php
    use App\Http\Controllers\AuthController;
use App\Http\Controllers\SinhVienController;
use App\Http\Middleware\JwtAuthenticate;
use App\Models\SinhVien;
use Illuminate\Support\Facades\Route;

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware([JwtAuthenticate::class])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);

    
});


//SinhVien
    Route::post('/addsinhvien',[SinhVienController::class, 'AddSinhVien']);
    Route::get('/danhsachsinhvien',[SinhVienController::class, 'AllSinhVien']);
    Route::post('/capnhatsinhvien',[SinhVienController::class, 'UpdateSinhVien']);
    Route::post('/xoasinhvien', [SinhVienController::class, 'DeleteSinhVien']);
    Route::get('/thongtinsinhviendangnhap', [SinhVienController::class , 'CurrentSinhVien']);