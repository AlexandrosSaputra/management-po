<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Suplier extends Model
{
    /** @use HasFactory<\Database\Factories\SuplierFactory> */
    use HasFactory;

    public function pre_orders() :HasMany
    {
        return $this->hasMany(PreOrder::class);
    }

    public function orders() : HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function kontraks() : HasMany {
        return $this->hasMany(Kontrak::class);
    }

    public function pembayarans() : HasMany {
        return $this->hasMany(Pembayaran::class);
    }

    public function template_orders() : HasMany {
        return $this->hasMany(TemplateOrder::class);
    }

    public function hargas() : HasMany {
        return $this->hasMany(Harga::class);
    }

    public function itemPenawarans() : HasMany {
        return $this->hasMany(ItemPenawaran::class);
    }

    public function cabang(): BelongsTo {
        return $this->belongsTo(Cabang::class);
    }
}
