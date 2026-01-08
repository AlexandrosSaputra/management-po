<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JudulDana extends Model
{
    // Gunakan koneksi PostgreSQL
    protected $connection = 'pgsql';

    // Tentukan nama tabel
    protected $table = 'dana.kegiatan';  // Contoh tabel PostgreSQL

    public function bidang(): BelongsTo {
        return $this->belongsTo(BidangDana::class, 'id_bid', 'id_bid');
    }
}
