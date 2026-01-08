<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Harga extends Model
{
    /** @use HasFactory<\Database\Factories\HargaFactory> */
    use HasFactory;

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function jenis() : BelongsTo {
        return $this->belongsTo(Jenis::class);
    }

    public function suplier() : BelongsTo {
        return $this->belongsTo(Suplier::class);
    }

    public function kontraks() : HasMany {
        return $this->hasMany(Kontrak::class);
    }

    public function itemPenawarans() : HasMany {
        return $this->hasMany(ItemPenawaran::class);
    }
}
