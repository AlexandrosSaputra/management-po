<?php

use App\Models\Pembayaran;
use App\Models\TipePembayaran;
use App\Models\User;
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
        Schema::create('arsip_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Pembayaran::class);
            $table->foreignIdFor(TipePembayaran::class)->nullable();
            $table->string('tgl_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_pembayarans');
    }
};
