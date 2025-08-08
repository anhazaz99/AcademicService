<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiaoVien extends Model
{
    protected $fillable = ['user_id', 'ma_gv', 'ho_ten', 'khoa_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function khoa()
    {
        return $this->belongsTo(Khoa::class);
    }

    public function lichHocs()
    {
        return $this->hasMany(LichHoc::class);
    }
}
