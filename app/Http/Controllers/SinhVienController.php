<?php

namespace App\Http\Controllers;

use App\Http\Requests\SinhVienRequest;
use App\Http\Requests\SinhVienUpdateRequest;
use App\Models\SinhVien;
use App\Models\User;
use Exception;
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
    // Lấy Thông Tin Sinh Viên
    public function  getSinhVienByUserId($user_id)
    {
        $sinhvien = SinhVien::with('user')->where('user_id', $user_id)->first();

        if (!$sinhvien) {
            return response()->json([
                'status' => false,
                'message' => 'Không Tìm Thấy Sinh Viên'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $sinhvien
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
    public function UpdateSinhVien(SinhVienUpdateRequest $request, $id)
    {
        $sinhvien = SinhVien::find($id);

        if (!$sinhvien) {
            return response()->json([
                'status' => false,
                'message' => 'Không Tìm Thấy Sinh Viên'
            ], 404);
        }

        try {
            DB::beginTransaction();

            $validated = $request->validated();

            // Loại bỏ các trường không liên quan đến bảng sinh_viens
            unset($validated['password'], $validated['password_confirmation']);

            $sinhvien->update($validated);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Cập nhật sinh viên thành công',
                'data' => $sinhvien
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Cập nhật thất bại',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // Xóa sinh viên
    public function DeleteSinhVien($id){
        $sinhvien = SinhVien::find($id);
        $user_id = $sinhvien->user_id;
        $user = User::where('id', $user_id)->first();
        try{
            if(!$sinhvien){
                return response()->json([
                    'status' => false,
                    'message' => 'Không Tìm Thấy Sinh Viên'
                ]);
            }

            $sinhvien->delete($id);
            $user->delete($user_id);
            return response()->json([
                'status' => true,
                'message' => 'Xóa Thành Công Sinh Viên',
            ],200);
        }
        catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Xóa Sinh Viên Thất Bại',
                'error' => $e->getMessage(),
            ],500);
        }
    }
}
