<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Khoa extends Model
{
    protected $fillable = ['ten_khoa'];

    public function giaoViens()
    {
        return $this->hasMany(GiaoVien::class);
    }

    public function lop()
    {
        return $this->hasMany(Lop::class);
    }
}
