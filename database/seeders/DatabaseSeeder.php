<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo admin mặc định
        User::create([
            'username' => 'admin',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
        ]);

        // Tạo giáo viên mẫu
        User::create([
            'username' => 'giaovien1',
            'password' => Hash::make('password'),
            'role' => 'giaovien',
        ]);

        // Tạo sinh viên mẫu
        User::create([
            'username' => 'sinhvien1',
            'password' => Hash::make('password'),
            'role' => 'sinhvien',
        ]);
    }
}
