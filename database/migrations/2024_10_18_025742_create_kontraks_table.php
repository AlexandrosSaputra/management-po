<?php

use App\Models\Gudang;
use App\Models\Harga;
use App\Models\Jenis;
use App\Models\order;
use App\Models\PreOrder;
use App\Models\Satuan;
use App\Models\Suplier;
use App\Models\TemplateOrder;
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
        Schema::create('kontraks', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal_mulai')->nullable();
            $table->string('tanggal_akhir')->nullable();
            $table->string('total_biaya')->nullable();
            $table->string('link_token')->nullable();
            $table->string('status')->default("kontrak"); // ditolak, preorder, selesai
            $table->string('token');
            $table->foreignIdFor(Order::class)->nullable();
            $table->foreignIdFor(Harga::class);
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Jenis::class);
            $table->foreignIdFor(Suplier::class);
            $table->foreignIdFor(Gudang::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontraks');
    }
};
