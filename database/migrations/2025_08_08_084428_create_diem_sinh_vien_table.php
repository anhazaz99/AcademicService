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
        Schema::create('diem_sinh_vien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sinh_vien_id')->constrained('sinh_viens')->onDelete('cascade');
            $table->foreignId('mon_hoc_id')->constrained('mon_hocs')->onDelete('cascade');
            $table->float('diem_TX'); // Điểm thường xuyên
            $table->integer('lan_thi')->default(1);
            $table->date('ngay_thi')->nullable();
            $table->float('diem_DK')->nullable(); // Điểm điều kiện
            $table->float('diem_thi')->nullable(); // Điểm thi
            $table->float('diemTB')->nullable(); // Điểm trung bình
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diem_sinh_vien');
    }
};
