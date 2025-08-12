<?php

namespace App\Helpers;

use App\Models\User;

class AuthHelper
{
    public static function getCurrentUser(): ?User
    {
        $userId = request()->attributes->get('jwt_sub');
        return $userId ? User::find($userId) : null;
    }

    public static function hasRole(string $role): bool
    {
        $user = self::getCurrentUser();
        return $user && $user->role === $role;
    }

    public static function hasAnyRole(array $roles): bool
    {
        $user = self::getCurrentUser();
        return $user && in_array($user->role, $roles);
    }

    public static function isAdmin(): bool
    {
        return self::hasRole('admin');
    }

    public static function isGiaoVien(): bool
    {
        return self::hasRole('giaovien');
    }

    public static function isSinhVien(): bool
    {
        return self::hasRole('sinhvien');
    }

    public static function canManageDiem(): bool
    {
        return self::isAdmin() || self::isGiaoVien();
    }

    public static function canViewDiem(): bool
    {
        return self::hasAnyRole(['admin', 'giaovien', 'sinhvien']);
    }
}