<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichHoc extends Model
{
    protected $fillable = ['mon_hoc_id', 'giao_vien_id', 'phong_id', 'thoi_gian'];

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class);
    }

    public function giaoVien()
    {
        return $this->belongsTo(GiaoVien::class);
    }

    public function phong()
    {
        return $this->belongsTo(Phong::class);
    }
}
