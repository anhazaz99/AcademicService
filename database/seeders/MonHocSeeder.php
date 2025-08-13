<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MonHoc;
use App\Models\Khoa;
use App\Models\GiaoVien;

class MonHocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy danh sách khoa và giáo viên để tạo môn học
        $khoas = Khoa::all();
        $giaoViens = GiaoVien::all();

        if ($khoas->isEmpty() || $giaoViens->isEmpty()) {
            $this->command->warn('Cần có dữ liệu khoa và giáo viên trước khi tạo môn học!');
            return;
        }

        $monHocs = [
            [
                'ten_mon' => 'Lập Trình Java',
                'so_tin_chi' => 3,
                'khoa_id' => $khoas->first()->id,
                'giao_vien_id' => $giaoViens->first()->id
            ],
            [
                'ten_mon' => 'Lập Trình Hướng Đối Tượng',
                'so_tin_chi' => 3,
                'khoa_id' => $khoas->first()->id,
                'giao_vien_id' => $giaoViens->first()->id
            ],
            [
                'ten_mon' => 'Lập Trình Cơ Bản',
                'so_tin_chi' => 4,
                'khoa_id' => $khoas->first()->id,
                'giao_vien_id' => $giaoViens->first()->id
            ],
            [
                'ten_mon' => 'Cơ Sở Dữ Liệu',
                'so_tin_chi' => 3,
                'khoa_id' => $khoas->first()->id,
                'giao_vien_id' => $giaoViens->first()->id
            ],
            [
                'ten_mon' => 'Lập Trình Web',
                'so_tin_chi' => 4,
                'khoa_id' => $khoas->first()->id,
                'giao_vien_id' => $giaoViens->first()->id
            ],
            [
                'ten_mon' => 'Mạng Máy Tính',
                'so_tin_chi' => 3,
                'khoa_id' => $khoas->first()->id,
                'giao_vien_id' => $giaoViens->first()->id
            ],
            [
                'ten_mon' => 'Trí Tuệ Nhân Tạo',
                'so_tin_chi' => 3,
                'khoa_id' => $khoas->first()->id,
                'giao_vien_id' => $giaoViens->first()->id
            ],
            [
                'ten_mon' => 'Đồ Họa Máy Tính',
                'so_tin_chi' => 3,
                'khoa_id' => $khoas->first()->id,
                'giao_vien_id' => $giaoViens->first()->id
            ]
        ];

        foreach ($monHocs as $monHoc) {
            MonHoc::create($monHoc);
        }

        $this->command->info('Đã tạo ' . count($monHocs) . ' môn học mẫu!');
    }
}
