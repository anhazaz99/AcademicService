<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonHocRequest;
use App\Models\MonHoc;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MonHocController extends Controller
{
    /**
     * Lấy danh sách tất cả môn học
     */
    public function index(): JsonResponse
    {
        $monHocs = MonHoc::with(['khoa', 'giaoVien'])->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $monHocs
        ]);
    }

    /**
     * Lấy thông tin môn học theo ID
     */
    public function show($id): JsonResponse
    {
        $monHoc = MonHoc::with(['khoa', 'giaoVien'])->find($id);
        
        if (!$monHoc) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy môn học'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $monHoc
        ]);
    }

    /**
     * Tạo môn học mới
     */
    public function store(MonHocRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        $monHoc = MonHoc::create($validated);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Tạo môn học thành công',
            'data' => $monHoc
        ], 201);
    }

    /**
     * Cập nhật môn học
     */
    public function update(MonHocRequest $request, $id): JsonResponse
    {
        $monHoc = MonHoc::find($id);
        
        if (!$monHoc) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy môn học'
            ], 404);
        }

        $validated = $request->validated();
        $monHoc->update($validated);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật môn học thành công',
            'data' => $monHoc
        ]);
    }

    /**
     * Xóa môn học
     */
    public function destroy($id): JsonResponse
    {
        $monHoc = MonHoc::find($id);
        
        if (!$monHoc) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy môn học'
            ], 404);
        }

        // Sử dụng method từ model để kiểm tra
        if (!$monHoc->canDelete()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể xóa môn học đang được sử dụng'
            ], 400);
        }

        $monHoc->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa môn học thành công'
        ]);
    }

    /**
     * Lấy môn học theo khoa
     */
    public function getByKhoa($khoaId): JsonResponse
    {
        // Sử dụng scope từ model
        $monHocs = MonHoc::byKhoa($khoaId)
            ->with(['khoa', 'giaoVien'])
            ->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $monHocs
        ]);
    }

    /**
     * Lấy môn học theo giáo viên
     */
    public function getByGiaoVien($giaoVienId): JsonResponse
    {
        // Sử dụng scope từ model
        $monHocs = MonHoc::byGiaoVien($giaoVienId)
            ->with(['khoa', 'giaoVien'])
            ->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $monHocs
        ]);
    }

    /**
     * Tìm kiếm môn học theo tên
     */
    public function search(Request $request): JsonResponse
    {
        $keyword = $request->get('keyword', '');
        
        // Sử dụng scope từ model
        $monHocs = MonHoc::searchByName($keyword)
            ->with(['khoa', 'giaoVien'])
            ->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $monHocs
        ]);
    }
}
