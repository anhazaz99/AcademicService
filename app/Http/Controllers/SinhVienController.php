<?php

namespace App\Http\Controllers;

use App\Http\Requests\SinhVienRequest;
use App\Models\SinhVien;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SinhVienController extends Controller
{
    // Hiển thị danh sách sinh viên
    public function AllSinhVien()
    {
        $sinhviens = SinhVien::with('user')->get();
        return response()->json([
            'status'=>true,
            'data'=>$sinhviens
        ]);
    }
    
    // Thông Tin Sinh Viên Đang Đăng Nhập
    public function CurrentSinhVien()
    {
        $user = Auth::user();
        $sinhvien = SinhVien::where('user_id', $user->id)->get();

        if(!$sinhvien){
            return response()->json([
                'status'=>false,
                'message'=> 'Không Tìm Thấy Sinh Viên'
            ]);
        }
        
        return response()->json([
            'status'=>true,
            'data'=>$sinhvien
        ]);
    }

    // Thêm sinh viên mới
    public function AddSinhVien(SinhVienRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction(); // Bắt đầu transaction

            // 1. Tạo user (tài khoản)
            $user = User::create([
                'username' => $validated['ma_sv'],
                'password' => Hash::make($validated['ma_sv']) // hoặc dùng password đã nhập
            ]);

            // 2. Gán user_id vào validated data
            $validated['user_id'] = $user->id;

            // 3. Tạo sinh viên
            $sinhvien = SinhVien::create($validated);

            DB::commit(); // Nếu mọi thứ ok -> lưu vào DB

            return response()->json([
                'status' => true,
                'message' => 'Thêm Sinh Viên Thành Công',
                'sinhvien' => $sinhvien,
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); // Nếu có lỗi -> rollback

            return response()->json([
                'status' => false,
                'message' => 'Thêm Sinh Viên Thất Bại',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Sửa sinh viên

    // Xóa sinh viên
}
