<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    protected $fillable = ['user_id', 'ma_sv', 'ho_ten', 'lop_id', 'gioi_tinh' ];

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

