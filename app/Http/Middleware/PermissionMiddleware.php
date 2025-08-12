<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user_Id = $request->attributes->get('jwt_sub');
        if(!$user_Id){
            throw new HttpResponseException(
                response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy thông tin người dùng'
                ], 401)
            );
        }
        $user = User::find($user_Id);
        if(!$user){
            throw new HttpResponseException(
                response()->json([
                    'status' => false,
                    'message' => 'Người dùng không tồn tại'
                ], 404)
            );
        }
        // Kiểm tra quyền dựa trên role và permission
        if (!$this->hasPermission($user, $permission)) {
            throw new HttpResponseException(
                response()->json([
                    'status' => false,
                    'message' => 'Bạn không có quyền thực hiện hành động này'
                ], 403)
            );
        }

        return $next($request);
    }
    
    private function hasPermission(User $user, string $permission): bool
    {
        $permissions = [
            'admin'=>[
                'user.manage', 'user.view', 'user.create', 'user.update', 'user.delete',
                'sinhvien.manage', 'sinhvien.view', 'sinhvien.create', 'sinhvien.update', 'sinhvien.delete',
                'giaovien.manage', 'giaovien.view', 'giaovien.create', 'giaovien.update', 'giaovien.delete',
                'diem.manage', 'diem.view', 'diem.create', 'diem.update', 'diem.delete',
                'monhoc.manage', 'monhoc.view', 'monhoc.create', 'monhoc.update', 'monhoc.delete',
                'khoa.manage', 'khoa.view', 'khoa.create', 'khoa.update', 'khoa.delete',
                'lop.manage', 'lop.view', 'lop.create', 'lop.update', 'lop.delete',
                'phong.manage', 'phong.view', 'phong.create', 'phong.update', 'phong.delete',
                'lichhoc.manage', 'lichhoc.view', 'lichhoc.create', 'lichhoc.update', 'lichhoc.delete'
            ],
            'giaovien'=>[
                'sinhvien.view',
                'diem.manage', 'diem.view', 'diem.create', 'diem.update',
                'monhoc.view',
                'khoa.view',
                'lop.view',
                'phong.view',
                'lichhoc.manage', 'lichhoc.view', 'lichhoc.create', 'lichhoc.update'
            ],
            'sinhvien'=>[
                'diem.view',
                'monhoc.view',
                'khoa.view',
                'lop.view',
                'phong.view',
                'lichhoc.view'
            ],
        ];
        return in_array($permission, $permissions[$user->role] ?? []);
    }
}
