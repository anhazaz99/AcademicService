<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiaoVien extends Model
{
    protected $fillable = [
        'user_id', 
        'ho_ten', 
        'gioi_tinh', 
        'email', 
        'dia_chi', 
        'sdt', 
        'khoa_id'
    ];

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