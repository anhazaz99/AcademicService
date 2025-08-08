<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diem extends Model
{
    protected $fillable = ['sinh_vien_id', 'mon_hoc_id', 'diem'];

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class);
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class);
    }
}
