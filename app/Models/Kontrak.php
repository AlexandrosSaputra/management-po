<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kontrak extends Model
{
    /** @use HasFactory<\Database\Factories\KontrakFactory> */
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function suplier() : BelongsTo {
        return $this->belongsTo(Suplier::class);
    }

    public function itemPenawarans() : HasMany {
        return $this->hasMany(ItemPenawaran::class);
    }

    public function jenis() : BelongsTo {
        return $this->belongsTo(Jenis::class);
    }

    public function gudang() : BelongsTo {
        return $this->belongsTo(Gudang::class);
    }

    public function template_order() : BelongsTo {
        return $this->belongsTo(TemplateOrder::class);
    }
}
