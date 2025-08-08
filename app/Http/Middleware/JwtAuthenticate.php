<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JwtAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        $authorization = (string) $request->header('Authorization', '');
        if (!str_starts_with($authorization, 'Bearer ')) {
            throw new UnauthorizedHttpException('Bearer', 'Thiếu header Authorization Bearer');
        }

        $token = substr($authorization, 7);
        if ($token === '') {
            throw new UnauthorizedHttpException('Bearer', 'Token rỗng');
        }

        $secret = (string) config('jwt.secret');
        if ($secret === '') {
            throw new UnauthorizedHttpException('Bearer', 'Thiếu cấu hình JWT secret');
        }

        try {
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            // Gắn thông tin userId (nếu có) vào request để controller sử dụng
            if (isset($decoded->sub)) {
                $request->attributes->set('jwt_sub', $decoded->sub);
            }
            $request->attributes->set('jwt_payload', (array) $decoded);
        } catch (\Throwable $e) {
            throw new UnauthorizedHttpException('Bearer', 'Token không hợp lệ: '.$e->getMessage());
        }

        return $next($request);
    }
}


