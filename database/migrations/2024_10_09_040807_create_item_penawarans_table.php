<?php

use App\Models\Harga;
use App\Models\Item;
use App\Models\Kontrak;
use App\Models\order;
use App\Models\Penawaran;
use App\Models\PreOrder;
use App\Models\Satuan;
use App\Models\Suplier;
use App\Models\TemplateOrder;
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
        Schema::create('item_penawarans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PreOrder::class)->nullable();
            $table->foreignIdFor(Kontrak::class)->nullable();
            $table->foreignIdFor(Item::class);
            $table->foreignIdFor(Satuan::class);
            $table->foreignIdFor(Order::class)->nullable();
            $table->foreignIdFor(TemplateOrder::class)->nullable();
            $table->foreignIdFor(Suplier::class)->nullable();
            $table->foreignIdFor(Harga::class)->nullable();
            $table->boolean('isRevisi')->default(false);
            $table->text('keterangan')->nullable();
            $table->float('potongan_harga')->nullable();
            $table->string('gambar')->nullable();
            $table->string('gambar_bukti_gudang')->nullable();
            $table->float('jumlah_revisi')->nullable();
            $table->decimal('harga_revisi', 15, 2)->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->float('jumlah')->nullable();
            $table->decimal('total_harga', 15, 2)->nullable();
            $table->decimal('total_harga_potongan', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_penawarans');
    }
};
