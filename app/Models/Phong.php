<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phong extends Model
{
    protected $fillable = ['ten_phong', 'so_luong'];

    public function lichHocs()
    {
        return $this->hasMany(LichHoc::class);
    }
}
