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
            $table->double('diem_TX');
            $table->integer('lan_thi')->default(1);
            $table->date('ngay_thi')->nullable();
            $table->double('diem_DK')->nullable();
            $table->double('diem_thi')->nullable();
            $table->double('diemTB')->nullable();
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
