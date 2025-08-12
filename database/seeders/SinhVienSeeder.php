<?php

namespace Database\Seeders;

use App\Models\SinhVien;
use App\Models\User;
use App\Models\Lop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SinhVienSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo user cho sinh viên
        $user = User::create([
            'username' => 'sinhvien1',
            'password' => Hash::make('password'),
            'role' => 'sinhvien',
        ]);

        // Tạo lớp mẫu (nếu chưa có)
        $lop = Lop::first();
        if (!$lop) {
            $lop = Lop::create([
                'ten_lop' => 'CNTT-K15',
                'khoa_id' => 1, // Giả sử có khoa ID 1
            ]);
        }

        // Tạo sinh viên
        SinhVien::create([
            'user_id' => $user->id,
            'ma_sv' => 'SV001',
            'ho_ten' => 'Nguyễn Văn A',
            'ngay_sinh' => '2000-01-01',
            'gioi_tinh' => 'Nam',
            'lop_id' => $lop->id,
            'dia_chi' => 'Hà Nội',
            'sdt' => '0123456789',
            'email' => 'sinhvien1@example.com',
        ]);

        // Tạo thêm sinh viên khác
        $user2 = User::create([
            'username' => 'sinhvien2',
            'password' => Hash::make('password'),
            'role' => 'sinhvien',
        ]);

        SinhVien::create([
            'user_id' => $user2->id,
            'ma_sv' => 'SV002',
            'ho_ten' => 'Trần Thị B',
            'ngay_sinh' => '2000-02-02',
            'gioi_tinh' => 'Nữ',
            'lop_id' => $lop->id,
            'dia_chi' => 'TP.HCM',
            'sdt' => '0987654321',
            'email' => 'sinhvien2@example.com',
        ]);
    }
}
