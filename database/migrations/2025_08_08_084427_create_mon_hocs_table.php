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
        Schema::create('mon_hocs', function (Blueprint $table) {
            $table->id();
            $table->string('ten_mon');
            $table->integer('so_tin_chi');
            $table->foreignId('khoa_id')->constrained('khoas')->onDelete('cascade');
            $table->foreignId('giao_vien_id')->constrained('giao_viens')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mon_hocs');
    }
};
