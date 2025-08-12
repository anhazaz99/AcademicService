<?php

namespace App\Http\Controllers;

use App\Http\Requests\diemSV\DiemRequest;
use App\Models\Diem;
use App\Models\SinhVien;
use App\Models\MonHoc;
use App\Helpers\AuthHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class DiemController extends Controller
{
    /**
     * Lấy danh sách điểm
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $currentUser = AuthHelper::getCurrentUser();
            $query = Diem::with(['sinhVien', 'monHoc']);

            // Admin xem tất cả điểm
            if (AuthHelper::isAdmin()) {
                if ($request->has('sinh_vien_id')) {
                    $query->where('sinh_vien_id', $request->sinh_vien_id);
                }
                if ($request->has('mon_hoc_id')) {
                    $query->where('mon_hoc_id', $request->mon_hoc_id);
                }
            }
            // Sinh viên chỉ xem điểm của mình
            else {
                $sinhVien = SinhVien::where('user_id', $currentUser->id)->first();
                if (!$sinhVien) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Không tìm thấy thông tin sinh viên'
                    ], 404);
                }
                $query->where('sinh_vien_id', $sinhVien->id);
            }

            $diems = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'status' => true,
                'message' => 'Lấy danh sách điểm thành công',
                'data' => $diems
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách điểm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Thêm điểm mới
     */
    public function store(DiemRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            
            // Kiểm tra xem đã có điểm cho sinh viên và môn học này chưa
            $existingDiem = Diem::where('sinh_vien_id', $validated['sinh_vien_id'])
                ->where('mon_hoc_id', $validated['mon_hoc_id'])
                ->where('lan_thi', $validated['lan_thi'] ?? 1)
                ->first();

            if ($existingDiem) {
                return response()->json([
                    'status' => false,
                    'message' => 'Đã tồn tại điểm cho sinh viên này ở lần thi này'
                ], 422);
            }

            // Tự động set ngày thi nếu không có
            if (!isset($validated['ngay_thi'])) {
                $validated['ngay_thi'] = now()->toDateString();
            }

            // Tạo instance để tính điểm TB
            $diem = new Diem($validated);
            
            // Tính điểm trung bình nếu có đủ điểm
            if (isset($validated['diem_DK']) && isset($validated['diem_thi'])) {
                $diem->diemTB = $diem->tinhDiemTB();
            }

            $diem->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Thêm điểm thành công',
                'data' => $diem->load(['sinhVien', 'monHoc'])
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi thêm điểm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xem chi tiết điểm
     */
    public function show(string $id): JsonResponse
    {
        try {
            $diem = Diem::with(['sinhVien', 'monHoc'])->find($id);

            if (!$diem) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy điểm'
                ], 404);
            }

            // Kiểm tra quyền xem điểm cụ thể
            $currentUser = AuthHelper::getCurrentUser();
            if (!AuthHelper::isAdmin()) {
                $sinhVien = SinhVien::where('user_id', $currentUser->id)->first();
                if ($diem->sinh_vien_id !== $sinhVien->id) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Bạn chỉ được xem điểm của mình'
                    ], 403);
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Lấy thông tin điểm thành công',
                'data' => $diem
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin điểm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật điểm
     */
    public function update(DiemRequest $request, string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $diem = Diem::find($id);

            if (!$diem) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy điểm'
                ], 404);
            }

            $validated = $request->validated();

            // Kiểm tra xem có điểm khác cùng sinh viên, môn học, lần thi không
            $existingDiem = Diem::where('sinh_vien_id', $validated['sinh_vien_id'])
                ->where('mon_hoc_id', $validated['mon_hoc_id'])
                ->where('lan_thi', $validated['lan_thi'] ?? 1)
                ->where('id', '!=', $id)
                ->first();

            if ($existingDiem) {
                return response()->json([
                    'status' => false,
                    'message' => 'Đã tồn tại điểm cho sinh viên này ở lần thi này'
                ], 422);
            }

            $diem->fill($validated);
            
            // Tính lại điểm trung bình nếu có đủ điểm
            if (isset($validated['diem_DK']) && isset($validated['diem_thi'])) {
                $diem->diemTB = $diem->tinhDiemTB();
            }

            $diem->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Cập nhật điểm thành công',
                'data' => $diem->load(['sinhVien', 'monHoc'])
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật điểm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa điểm
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $diem = Diem::find($id);

            if (!$diem) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy điểm'
                ], 404);
            }

            $diem->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Xóa điểm thành công'
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi xóa điểm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy điểm của sinh viên cụ thể
     */
    public function getBySinhVien(string $sinhVienId): JsonResponse
    {
        try {
            $currentUser = AuthHelper::getCurrentUser();
            
            if (!AuthHelper::isAdmin()) {
                $sinhVien = SinhVien::where('user_id', $currentUser->id)->first();
                if ($sinhVien->id != $sinhVienId) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Bạn chỉ được xem điểm của mình'
                    ], 403);
                }
            }

            $diems = Diem::with(['monHoc'])
                ->where('sinh_vien_id', $sinhVienId)
                ->orderBy('mon_hoc_id')
                ->orderBy('lan_thi')
                ->get();

            $diemTrungBinh = Diem::tinhDiemTrungBinh($sinhVienId);

            return response()->json([
                'status' => true,
                'message' => 'Lấy điểm sinh viên thành công',
                'data' => [
                    'diems' => $diems,
                    'diem_trung_binh' => $diemTrungBinh
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi lấy điểm sinh viên',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sinh viên xem điểm của mình
     */
    public function myScores(): JsonResponse
    {
        try {
            $currentUser = AuthHelper::getCurrentUser();
            
            if (!$currentUser || $currentUser->role !== 'sinhvien') {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn không phải là sinh viên'
                ], 403);
            }

            $sinhVien = SinhVien::where('user_id', $currentUser->id)->first();

            if (!$sinhVien) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy thông tin sinh viên'
                ], 404);
            }

            $diems = Diem::with(['monHoc'])
                ->where('sinh_vien_id', $sinhVien->id)
                ->orderBy('mon_hoc_id')
                ->orderBy('lan_thi')
                ->get();

            $diemTrungBinh = Diem::tinhDiemTrungBinh($sinhVien->id);

            return response()->json([
                'status' => true,
                'message' => 'Lấy điểm thành công',
                'data' => [
                    'sinh_vien' => $sinhVien,
                    'diems' => $diems,
                    'diem_trung_binh' => $diemTrungBinh
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi lấy điểm',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
