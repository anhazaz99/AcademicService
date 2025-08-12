<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    protected $fillable = ['user_id', 'ho_ten', 'ngay_sinh', 'gioi_tinh', 'lop_id', 'dia_chi', 'sdt', 'email' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lop()
    {
        return $this->belongsTo(Lop::class);
    }

    public function diem()
    {
        return $this->hasMany(Diem::class);
    }
}

