<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonHoc extends Model
{
    protected $fillable = ['ten_mon', 'so_tin_chi','khoa_id' , 'giao_vien_id'];

    public function lichHocs()
    {
        return $this->hasMany(LichHoc::class);
    }

    public function diem()
    {
        return $this->hasMany(Diem::class);
    }
}
