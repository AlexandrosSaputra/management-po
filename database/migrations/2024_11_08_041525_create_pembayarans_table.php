<?php

use App\Models\ArsipPembayaran;
use App\Models\Cabang;
use App\Models\Gudang;
use App\Models\Jenis;
use App\Models\Suplier;
use App\Models\TipePembayaran;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Suplier::class);
            $table->foreignIdFor(Gudang::class);
            $table->foreignIdFor(ArsipPembayaran::class);
            $table->foreignIdFor(User::class, 'kasir_id')->nullable();
            $table->foreignIdFor(Cabang::class);
            $table->string('foto')->nullable();
            $table->string('periode_tgl');
            $table->string('sampai_tgl');
            $table->string('total_tagihan')->nullable();
            $table->text('link_token')->nullable();
            $table->string('token')->nullable();
            $table->string('status')->default('proses'); // proses, diterima
            $table->boolean('is_pendanaan')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
