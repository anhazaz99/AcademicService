<?php

namespace App\Http\Controllers;

use App\Http\Requests\GIaoVien\GiaovienRequest;
use App\Http\Requests\GIaoVien\UpdateGV;
use App\Models\GiaoVien;
use App\Models\User;
use App\Helpers\AuthHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Exception;

class GiaoVienController extends Controller
{
    /**
     * Lấy danh sách giáo viên (kèm user & khoa)
     */
    public function index(): JsonResponse
    {
        try {
            // Chỉ admin mới được xem tất cả giáo viên
            if (!AuthHelper::isAdmin()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn không có quyền xem danh sách giáo viên'
                ], 403);
            }

            $giaoViens = GiaoVien::with(['user', 'khoa'])->get();
            
            return response()->json([
                'status' => true,
                'message' => 'Lấy danh sách giáo viên thành công',
                'data' => $giaoViens
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách giáo viên',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Thêm mới giáo viên
     */
    public function store(GiaovienRequest $request): JsonResponse
    {
        try {
            // Chỉ admin mới được thêm giáo viên
            if (!AuthHelper::isAdmin()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn không có quyền thêm giáo viên'
                ], 403);
            }

            DB::beginTransaction();
            
            $validated = $request->validated();
            
            // Kiểm tra ma_gv đã tồn tại chưa
            if (GiaoVien::where('ma_gv', $validated['ma_gv'])->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mã giáo viên đã tồn tại'
                ], 422);
            }
            
            // Kiểm tra email đã tồn tại chưa
            if (GiaoVien::where('email', $validated['email'])->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email đã tồn tại'
                ], 422);
            }
            
            // Tạo user mới tự động
            $user = User::create([
                'username' => $validated['ma_gv'],
                'password' => Hash::make($validated['ma_gv']), // Mật khẩu mặc định
                'role' => 'giaovien'
            ]);
            
            // Gán user_id mới tạo
            $validated['user_id'] = $user->id;
            
            $giaoVien = GiaoVien::create($validated);
            
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Thêm giáo viên thành công',
                'data' => $giaoVien->load(['user', 'khoa'])
            ], 201);
            
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Lỗi cơ sở dữ liệu khi thêm giáo viên',
                'error' => $e->getMessage()
            ], 500);
            
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi thêm giáo viên',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xem chi tiết 1 giáo viên
     */
    public function show(string $id): JsonResponse
    {
        try {
            // Kiểm tra ID có hợp lệ không
            if (!ctype_digit($id) || (int)$id <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID giáo viên không hợp lệ'
                ], 400);
            }
            
            $giaoVien = GiaoVien::with(['user', 'khoa', 'lichHocs'])->find($id);

            if (!$giaoVien) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy giáo viên'
                ], 404);
            }

            $currentUser = AuthHelper::getCurrentUser();
            
            // Kiểm tra quyền xem thông tin giáo viên
            if (!AuthHelper::isAdmin()) {
                // Giáo viên chỉ được xem thông tin của mình
                if ($currentUser->role === 'giaovien') {
                    $currentGiaoVien = GiaoVien::where('user_id', $currentUser->id)->first();
                    if (!$currentGiaoVien || $currentGiaoVien->id != $id) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Bạn chỉ được xem thông tin của mình'
                        ], 403);
                    }
                }
                // Sinh viên không được xem thông tin giáo viên
                else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Bạn không có quyền xem thông tin giáo viên'
                    ], 403);
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Lấy thông tin giáo viên thành công',
                'data' => $giaoVien
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin giáo viên',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật thông tin giáo viên
     */
    public function update(UpdateGV $request, string $id): JsonResponse
    {
        try {
            // Kiểm tra ID có hợp lệ không
            if (!is_numeric($id) || $id <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID giáo viên không hợp lệ'
                ], 400);
            }
            
            $giaoVien = GiaoVien::find($id);

            if (!$giaoVien) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy giáo viên'
                ], 404);
            }

            $currentUser = AuthHelper::getCurrentUser();
            
            // Kiểm tra quyền cập nhật
            if (!AuthHelper::isAdmin()) {
                // Giáo viên chỉ được cập nhật thông tin của mình
                if ($currentUser->role === 'giaovien') {
                    $currentGiaoVien = GiaoVien::where('user_id', $currentUser->id)->first();
                    if (!$currentGiaoVien || $currentGiaoVien->id != $id) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Bạn chỉ được cập nhật thông tin của mình'
                        ], 403);
                    }
                }
                // Sinh viên không được cập nhật thông tin giáo viên
                else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Bạn không có quyền cập nhật thông tin giáo viên'
                    ], 403);
                }
            }

            DB::beginTransaction();

            $validated = $request->validated();
            
            // Kiểm tra ma_gv đã tồn tại chưa (bỏ qua record hiện tại)
            if (GiaoVien::where('ma_gv', $validated['ma_gv'])->where('id', '!=', $id)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mã giáo viên đã tồn tại'
                ], 422);
            }
            
            // Kiểm tra email đã tồn tại chưa (bỏ qua record hiện tại)
            if (GiaoVien::where('email', $validated['email'])->where('id', '!=', $id)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email đã tồn tại'
                ], 422);
            }
            
            $giaoVien->update($validated);
            
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Cập nhật giáo viên thành công',
                'data' => $giaoVien->load(['user', 'khoa'])
            ], 200);
            
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Lỗi cơ sở dữ liệu khi cập nhật giáo viên',
                'error' => $e->getMessage()
            ], 500);
            
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật giáo viên',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa giáo viên
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            // Chỉ admin mới được xóa giáo viên
            if (!AuthHelper::isAdmin()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn không có quyền xóa giáo viên'
                ], 403);
            }

            DB::beginTransaction();
            
            // Kiểm tra ID có hợp lệ không
            if (!is_numeric($id) || $id <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID giáo viên không hợp lệ'
                ], 400);
            }
            
            $giaoVien = GiaoVien::find($id);

            if (!$giaoVien) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy giáo viên'
                ], 404);
            }

            // Kiểm tra xem giáo viên có đang được sử dụng không
            if ($giaoVien->lichHocs()->count() > 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không thể xóa giáo viên vì đang có lịch học'
                ], 400);
            }
            
            // Xóa user liên quan
            if ($giaoVien->user) {
                $giaoVien->user->delete();
            }
            
            $giaoVien->delete();
            
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Xóa giáo viên thành công'
            ], 200);
            
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Lỗi cơ sở dữ liệu khi xóa giáo viên',
                'error' => $e->getMessage()
            ], 500);
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi xóa giáo viên',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Giáo viên xem thông tin của mình
     */
    public function myProfile(): JsonResponse
    {
        try {
            $currentUser = AuthHelper::getCurrentUser();
            
            if (!$currentUser || $currentUser->role !== 'giaovien') {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn không phải là giáo viên'
                ], 403);
            }

            $giaoVien = GiaoVien::where('user_id', $currentUser->id)
                ->with(['user', 'khoa'])
                ->first();

            if (!$giaoVien) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy thông tin giáo viên'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Lấy thông tin giáo viên thành công',
                'data' => $giaoVien
            ], 200);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin giáo viên',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}