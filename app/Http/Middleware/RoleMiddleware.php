<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {   
        $userId = $request->attributes->get('jwt_sub');
        
        // Debug log
        \Log::info('RoleMiddleware - User ID: ' . $userId);
        
        if(!$userId){
            throw new HttpResponseException(
                response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy thông tin người dùng'
                ], 401)
            );
        }
        $user = User::find($userId);
        
        // Debug log
        \Log::info('RoleMiddleware - User: ' . json_encode($user));
        \Log::info('RoleMiddleware - Required roles: ' . json_encode($roles));
        
        if(!$user){
            throw new HttpResponseException(
                response()->json([
                    'status' => false,
                    'message' => 'Người dùng không tồn tại'
                ], 404)
            );
        }
        
        // Kiểm tra xem role của user có trong danh sách roles được phép không
        if (!in_array($user->role, $roles)) {
            \Log::info('RoleMiddleware - Access denied. User role: ' . $user->role);
            throw new HttpResponseException(
                response()->json([
                    'status' => false,
                    'message' => 'Bạn không có quyền truy cập chức năng này. User role: ' . $user->role . ', Required: ' . implode(',', $roles)
                ], 403)
            );
        }
        
        $request->attributes->set('current_user', $user);
        return $next($request);
    }
}
