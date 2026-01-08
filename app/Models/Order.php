<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    public function pre_order() : BelongsTo {
        return $this->belongsTo(PreOrder::class);
    }

    public function jenis() : BelongsTo {
        return $this->belongsTo(Jenis::class);
    }

    public function gudang() : BelongsTo {
        return $this->belongsTo(Gudang::class);
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function suplier() : BelongsTo {
        return $this->belongsTo(Suplier::class);
    }

    public function pembayaran() : BelongsTo {
        return $this->belongsTo(Pembayaran::class);
    }

    public function kontrak() : BelongsTo {
        return $this->belongsTo(Kontrak::class);
    }

    public function itemPenawarans() : HasMany {
        return $this->hasMany(ItemPenawaran::class);
    }
}
