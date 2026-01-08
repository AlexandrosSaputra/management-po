<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipPembayaran extends Model
{
    /** @use HasFactory<\Database\Factories\ArsipPembayaranFactory> */
    use HasFactory;

    public function pembayaran() : BelongsTo{
        return $this->belongsTo(Pembayaran::class);
    }

    public function tipePembayaran() : BelongsTo{
        return $this->belongsTo(TipePembayaran::class);
    }
}
