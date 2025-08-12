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
        Schema::table('giao_viens', function (Blueprint $table) {
            if (!Schema::hasColumn('giao_viens', 'gioi_tinh')) {
                $table->enum('gioi_tinh', ['Nam', 'Nữ'])->nullable()->after('ho_ten');
            }
            if (!Schema::hasColumn('giao_viens', 'email')) {
                $table->string('email')->unique()->after('gioi_tinh');
            }
            if (!Schema::hasColumn('giao_viens', 'dia_chi')) {
                $table->text('dia_chi')->nullable()->after('email');
            }
            if (!Schema::hasColumn('giao_viens', 'sdt')) {
                $table->string('sdt', 20)->nullable()->after('dia_chi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('giao_viens', function (Blueprint $table) {
            $table->dropColumn(['gioi_tinh', 'email', 'dia_chi', 'sdt']);
        });
    }
};
