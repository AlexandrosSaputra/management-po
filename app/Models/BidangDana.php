<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BidangDana extends Model
{
    // Gunakan koneksi PostgreSQL
    protected $connection = 'pgsql';

    // Tentukan nama tabel
    protected $table = 'dana.bidang';  // Contoh tabel PostgreSQL

    public function kegiatans(): HasMany
    {
        return $this->hasMany(JudulDana::class, 'id_bid', 'id_bid');
    }
}
