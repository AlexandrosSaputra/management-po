<?php

use App\Models\Admin;
use App\Models\Dapur;
use App\Models\Invoice;
use App\Models\Jenis;
use App\Models\Kontrak;
use App\Models\order;
use App\Models\Pemesan;
use App\Models\Penawaran;
use App\Models\PreOrder;
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
        Schema::create('pre_orders', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['penawaran', 'dikirim', 'ditolak', 'diterima', 'invalid'])->default("penawaran"); // penawaran, dikirim, ditolak, diterima, invalid
            $table->string('token')->nullable();
            $table->boolean('isOrdered')->default(false);
            $table->text('catatan_suplier')->nullable();
            $table->text('link_token')->nullable();
            $table->foreignIdFor(TemplateOrder::class)->nullable();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Jenis::class);
            $table->foreignIdFor(Suplier::class);
            $table->foreignIdFor(Order::class)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_orders');
    }
};
