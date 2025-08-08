<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lop extends Model
{
    protected $fillable = ['ten_lop', 'khoa_id'];

    public function khoa()
    {
        return $this->belongsTo(Khoa::class);
    }

    public function sinhViens()
    {
        return $this->hasMany(SinhVien::class);
    }
}
