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
        // Xóa tất cả foreign key constraints trước
        Schema::table('lich_hocs', function (Blueprint $table) {
            $table->dropForeign(['giao_vien_id']);
        });

        Schema::table('mon_hocs', function (Blueprint $table) {
            $table->dropForeign(['giao_vien_id']);
        });

        // Xóa bảng cũ
        Schema::dropIfExists('giao_viens');

        // Tạo bảng mới với cấu trúc đầy đủ
        Schema::create('giao_viens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ho_ten');
            $table->enum('gioi_tinh', ['Nam', 'Nữ'])->nullable();
            $table->string('email')->unique();
            $table->text('dia_chi')->nullable();
            $table->string('sdt', 20)->nullable();
            $table->string('hoc_vi')->nullable();
            $table->foreignId('khoa_id')->constrained('khoas')->onDelete('cascade');
            $table->timestamps();
        });

        // Tạo lại foreign key constraints
        Schema::table('lich_hocs', function (Blueprint $table) {
            $table->foreignId('giao_vien_id')->constrained('giao_viens')->onDelete('cascade');
        });

        Schema::table('mon_hocs', function (Blueprint $table) {
            $table->foreignId('giao_vien_id')->constrained('giao_viens')->onDelete('cascade');
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
