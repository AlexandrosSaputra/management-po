<?php

use App\Models\Jenis;
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
        Schema::create('hargas', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default("aktif"); // aktif, tidakaktif
            $table->string('token');
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Jenis::class);
            $table->foreignIdFor(Suplier::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hargas');
    }
};
