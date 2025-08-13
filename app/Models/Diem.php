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
        'diemTB',
    ];

    protected $casts = [
        'diem_TX' => 'double',
        'diem_DK' => 'double',
        'diem_thi' => 'double',
        'diemTB' => 'double',
        'lan_thi' => 'integer',
        'ngay_thi' => 'date',
    ];

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class);
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class);
    }
}
