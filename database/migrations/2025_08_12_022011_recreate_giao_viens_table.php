<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tạo bảng mới với cấu trúc đầy đủ
        Schema::create('giao_viens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ma_gv', 50)->unique(); // Thêm mã giáo viên
            $table->string('ho_ten');
            $table->enum('gioi_tinh', ['Nam', 'Nữ'])->nullable();
            $table->string('email')->unique();
            $table->text('dia_chi')->nullable();
            $table->string('sdt', 20)->nullable();
            $table->foreignId('khoa_id')->constrained('khoas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giao_viens');
    }
};
