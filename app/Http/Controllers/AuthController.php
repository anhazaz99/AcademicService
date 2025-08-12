<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\SinhVien;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Đăng ký nhé ae
    public function register(RegisterRequest  $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['username'] = Str::lower($validated['username']);
        $user = User::create($validated);
        return response()->json([
            'status' => 'success',
            'message' => 'Đăng ký thành công.',
            'user' => $user,
        ]);
    }
    
    // Đăng nhập nhé ae
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $username = Str::lower($validated['username']);
        $user = User::where('username', $username)->first();
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Tên đăng nhập hoặc mật khẩu không đúng.'],
            ]);
        }
        $payload = [
            'iss' => 'AcademicService', 
            'sub' => $user->id,
            'role' => $user->role,//them role vao payload
            'iat' => time(),
            'exp' => time() + 60 * 60, // Token có hiệu lực trong 1 giờ
        ];
        $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
        return response()->json([
            'status' => 'success',
            'message' => 'Đăng nhập thành công.',
            'access_token' => $jwt,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
            ]
        ]);
    }

    public function me(Request $request)
    {
        $payload = (array) $request->attributes->get('jwt_payload', []);
        $userId = $payload['sub'] ?? null;
        $user = $userId ? User::find($userId) : null;
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'Người dùng không tồn tại',
            ],404);
        }
        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
            ]
        ]);
    }
}


