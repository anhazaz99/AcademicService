<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
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
    public function login(RegisterRequest $request)
    {
      
    }

    public function me(Request $request)
    {
        $payload = (array) $request->attributes->get('jwt_payload', []);
        $userId = $payload['sub'] ?? null;
        $user = $userId ? User::find($userId) : null;
        return response()->json(['user' => $user]);
    }
}


