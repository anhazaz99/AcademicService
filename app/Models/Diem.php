<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diem extends Model
{
    protected $table = 'diem_sinh_vien';
    
    protected $fillable = [
        'sinh_vien_id', 
        'mon_hoc_id', 
        'diem_TX',
        'lan_thi',
        'ngay_thi',
        'diem_DK',
        'diem_thi',
        'diemTB'
    ];

    protected $casts = [
        'diem_TX' => 'float',
        'diem_DK' => 'float',
        'diem_thi' => 'float',
        'diemTB' => 'float',
        'lan_thi' => 'integer',
        'ngay_thi' => 'date',
    ];

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class);
    }

    // Tạm thời comment relationship này
    // public function monHoc()
    // {
    //     return $this->belongsTo(MonHoc::class);
    // }

    // Tính điểm trung bình của sinh viên
    public static function tinhDiemTrungBinh($sinhVienId)
    {
        $diem = self::where('sinh_vien_id', $sinhVienId)
            ->selectRaw('AVG(diemTB) as diem_trung_binh')
            ->first();
        
        return round($diem->diem_trung_binh ?? 0, 2);
    }

    // Tính điểm trung bình cho một môn học
    public function tinhDiemTB()
    {
        // Công thức: (diem_TX * 0.3) + (diem_DK * 0.3) + (diem_thi * 0.4)
        $diemTB = ($this->diem_TX * 0.3) + ($this->diem_DK * 0.3) + ($this->diem_thi * 0.4);
        return round($diemTB, 2);
    }

    // Lấy điểm cao nhất của sinh viên cho môn học
    public static function layDiemCaoNhat($sinhVienId, $monHocId)
    {
        return self::where('sinh_vien_id', $sinhVienId)
            ->where('mon_hoc_id', $monHocId)
            ->max('diemTB');
    }
}
