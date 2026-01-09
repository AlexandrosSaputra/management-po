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
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn([
                'is_pendanaan',
                'cabang_dana_id',
                'divisi_project_dana_id',
                'judul_dana_id',
                'anggaran_dana_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->boolean('is_pendanaan')->nullable();

            $table->unsignedBigInteger('cabang_dana_id')->nullable();
            $table->unsignedBigInteger('divisi_project_dana_id')->nullable();
            $table->unsignedBigInteger('judul_dana_id')->nullable();
            $table->unsignedBigInteger('anggaran_dana_id')->nullable();
        });
    }
};
