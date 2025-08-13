<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonHoc extends Model
{
    protected $fillable = [
        'ten_mon', 
        'so_tin_chi',
        'khoa_id', 
        'giao_vien_id'
    ];

    protected $casts = [
        'so_tin_chi' => 'integer',
        'khoa_id' => 'integer',
        'giao_vien_id' => 'integer'
    ];

    /**
     * Lấy thông tin khoa của môn học
     */
    public function khoa(): BelongsTo
    {
        return $this->belongsTo(Khoa::class);
    }

    /**
     * Lấy thông tin giáo viên phụ trách môn học
     */
    public function giaoVien(): BelongsTo
    {
        return $this->belongsTo(GiaoVien::class);
    }

    /**
     * Lấy danh sách lịch học của môn học
     */
    public function lichHocs(): HasMany
    {
        return $this->hasMany(LichHoc::class);
    }

    /**
     * Lấy danh sách điểm của môn học
     */
    public function diem(): HasMany
    {
        return $this->hasMany(Diem::class);
    }

    /**
     * Scope để lấy môn học theo khoa
     */
    public function scopeByKhoa($query, $khoaId)
    {
        return $query->where('khoa_id', $khoaId);
    }

    /**
     * Scope để lấy môn học theo giáo viên
     */
    public function scopeByGiaoVien($query, $giaoVienId)
    {
        return $query->where('giao_vien_id', $giaoVienId);
    }

    /**
     * Scope để tìm kiếm môn học theo tên
     */
    public function scopeSearchByName($query, $keyword)
    {
        return $query->where('ten_mon', 'like', "%{$keyword}%");
    }

    /**
     * Kiểm tra xem môn học có thể xóa được không
     */
    public function canDelete(): bool
    {
        return $this->lichHocs()->count() === 0 && $this->diem()->count() === 0;
    }
}
