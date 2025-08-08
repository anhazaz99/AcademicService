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
        Schema::create('lich_hocs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mon_hoc_id')->constrained('mon_hocs')->onDelete('cascade');
            $table->foreignId('lop_id')->constrained('lops')->onDelete('cascade');
            $table->foreignId('phong_id')->constrained('phongs')->onDelete('cascade');
            $table->date('ngay_hoc');
            $table->integer('tiet_bat_dau');
            $table->integer('so_tiet');
            $table->foreignId('giao_vien_id')->constrained('giao_viens')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_hocs');
    }
};
