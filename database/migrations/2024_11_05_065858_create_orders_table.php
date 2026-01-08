<?php

use App\Models\Gudang;
use App\Models\Jenis;
use App\Models\Kontrak;
use App\Models\Pembayaran;
use App\Models\PreOrder;
use App\Models\Suplier;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('total_biaya')->nullable();
            $table->string('target_kirim')->nullable();
            $table->string('tanggal_po')->nullable();
            $table->string('status')->default('preorder'); // preorder(admin menentukan jumlah item), terkirim(admin minta konfirmasi ke suplier), onprocess(suplier menerima + proses mengirim ke gudang), diterima(gudang menerima + confirm ke admin), revisi(admin tidak confirm dari gudang + confirm ke suplier), revisi diterima (revisi admin diterima suplier), revisi ditolak(revisi admin ditolak suplier dan admin), invalid (data didisable oleh super admin)
            $table->string('token');
            $table->string('foto')->nullable();
            $table->string('invoice_suplier')->nullable();
            $table->boolean('isKontrak');
            $table->boolean('isNonpo')->nullable();
            $table->text('catatan_suplier')->nullable();
            $table->text('catatan_gudang')->nullable();
            $table->text('link_token')->nullable();
            $table->string('kode', 255)->unique()->nullable();
            $table->date('tgl_selesai')->nullable();
            // $table->boolean('isTelat')->default(false);
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Suplier::class);
            $table->foreignIdFor(Pembayaran::class)->nullable();
            $table->foreignIdFor(PreOrder::class)->nullable();
            $table->foreignIdFor(Kontrak::class)->nullable();
            $table->foreignIdFor(Gudang::class)->nullable();
            $table->foreignIdFor(Jenis::class)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
