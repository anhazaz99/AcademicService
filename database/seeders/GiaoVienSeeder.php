<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Khoa;
use App\Models\GiaoVien;
use Illuminate\Support\Facades\Hash;

class GiaoVienSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo khoa mẫu
        $khoaCNTT = Khoa::firstOrCreate(['ten_khoa' => 'Công nghệ thông tin']);
        $khoaKT = Khoa::firstOrCreate(['ten_khoa' => 'Kinh tế']);
        $khoaNN = Khoa::firstOrCreate(['ten_khoa' => 'Ngoại ngữ']);

        // Tạo users mẫu
        $users = [
            [
                'username' => 'gv001',
                'password' => Hash::make('123456'),
                'role' => 'giaovien'
            ],
            [
                'username' => 'gv002', 
                'password' => Hash::make('123456'),
                'role' => 'giaovien'
            ],
            [
                'username' => 'gv003',
                'password' => Hash::make('123456'), 
                'role' => 'giaovien'
            ]
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['username' => $userData['username']],
                $userData
            );
        }

        // Tạo giáo viên mẫu
        $giaoViens = [
            [
                'user_id' => User::where('username', 'gv001')->first()->id,
                'ho_ten' => 'Nguyễn Văn An',
                'gioi_tinh' => 'Nam',
                'email' => 'nguyenvanan@example.com',
                'dia_chi' => '123 Đường ABC, Quận 1, TP.HCM',
                'sdt' => '0901234567',
                'khoa_id' => $khoaCNTT->id
            ],
            [
                'user_id' => User::where('username', 'gv002')->first()->id,
                'ho_ten' => 'Trần Thị Bình',
                'gioi_tinh' => 'Nữ',
                'email' => 'tranthibinh@example.com',
                'dia_chi' => '456 Đường XYZ, Quận 3, TP.HCM',
                'sdt' => '0909876543',
                'khoa_id' => $khoaKT->id
            ],
            [
                'user_id' => User::where('username', 'gv003')->first()->id,
                'ho_ten' => 'Lê Văn Cường',
                'gioi_tinh' => 'Nam',
                'email' => 'levancuong@example.com',
                'dia_chi' => '789 Đường DEF, Quận 7, TP.HCM',
                'sdt' => '0905555666',
                'khoa_id' => $khoaNN->id
            ]
        ];

        foreach ($giaoViens as $giaoVienData) {
            GiaoVien::firstOrCreate(
                ['email' => $giaoVienData['email']],
                $giaoVienData
            );
        }
    }
}