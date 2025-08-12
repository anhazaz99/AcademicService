<?php

namespace App\Http\Controllers;

use App\Http\Requests\GiaovienRequest;
use App\Models\GiaoVien;
use Illuminate\Http\Request;

class GiaoVienController extends Controller
{
    /**
     * Lấy danh sách giáo viên (kèm user & khoa)
     */
    public function index()
    {
        $giaoViens = GiaoVien::with(['user', 'khoa'])->get();
        return response()->json($giaoViens);
    }

    /**
     * Thêm mới giáo viên
     */
    public function store(GiaovienRequest $request)
    {
        $validated = $request->validated();
        $giaoVien = GiaoVien::create($validated);

        return response()->json([
            'message' => 'Thêm giáo viên thành công',
            'data' => $giaoVien->load(['user', 'khoa'])
        ], 201);
    }

    /**
     * Xem chi tiết 1 giáo viên
     */
    public function show(string $id)
    {
        $giaoVien = GiaoVien::with(['user', 'khoa', 'lichHocs'])->find($id);

        if (!$giaoVien) {
            return response()->json(['message' => 'Không tìm thấy giáo viên'], 404);
        }

        return response()->json($giaoVien);
    }

    /**
     * Cập nhật thông tin giáo viên
     */
    public function update(GiaovienRequest $request, string $id)
    {
        $giaoVien = GiaoVien::find($id);

        if (!$giaoVien) {
            return response()->json(['message' => 'Không tìm thấy giáo viên'], 404);
        }

        $validated = $request->validated();
        $giaoVien->update($validated);

        return response()->json([
            'message' => 'Cập nhật giáo viên thành công',
            'data' => $giaoVien->load(['user', 'khoa'])
        ]);
    }

    /**
     * Xóa giáo viên
     */
    public function destroy(string $id)
    {
        $giaoVien = GiaoVien::find($id);

        if (!$giaoVien) {
            return response()->json(['message' => 'Không tìm thấy giáo viên'], 404);
        }

        $giaoVien->delete();

        return response()->json(['message' => 'Xóa giáo viên thành công']);
    }
}
