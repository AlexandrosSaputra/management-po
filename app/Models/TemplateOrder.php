<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateOrder extends Model
{
    /** @use HasFactory<\Database\Factories\TemplateOrderFactory> */
    use HasFactory;

    // $table->foreignIdFor(User::class);
    //         $table->foreignIdFor(Jenis::class);
    //         $table->foreignIdFor(Suplier::class);
    //         $table->foreignIdFor(Gudang::class);

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function jenis() : BelongsTo {
        return $this->belongsTo(Jenis::class);
    }

    public function suplier() : BelongsTo {
        return $this->belongsTo(Suplier::class);
    }

    public function gudang() : BelongsTo {
        return $this->belongsTo(Gudang::class);
    }

    public function kontraks() : HasMany {
        return $this->hasMany(Kontrak::class);
    }

    public function itemPenawarans() : HasMany {
        return $this->hasMany(ItemPenawaran::class);
    }
}
