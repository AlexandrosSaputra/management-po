<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembayaran extends Model
{
    /** @use HasFactory<\Database\Factories\PembayaranFactory> */
    use HasFactory;

    public function gudang() : BelongsTo {
        return $this->belongsTo(Gudang::class);
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function suplier() : BelongsTo {
        return $this->belongsTo(Suplier::class);
    }

    public function orders() : HasMany {
        return $this->hasMany(Order::class);
    }

    public function arsipPembayaran() : BelongsTo{
        return $this->belongsTo(ArsipPembayaran::class);
    }
}
