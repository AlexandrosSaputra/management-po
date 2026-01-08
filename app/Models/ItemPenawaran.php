<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemPenawaran extends Model
{
    /** @use HasFactory<\Database\Factories\ItemPenawaranFactory> */
    use HasFactory;

    public function pre_order()
    {
        return $this->belongsTo(PreOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function kontrak()
    {
        return $this->belongsTo(Kontrak::class);
    }

    public function order() : BelongsTo {
        return $this->belongsTo(Order::class);
    }

    public function template_order() : BelongsTo {
        return $this->belongsTo(TemplateOrder::class);
    }

    public function harga() : BelongsTo {
        return $this->belongsTo(Harga::class);
    }

    public function suplier() : BelongsTo {
        return $this->belongsTo(Suplier::class);
    }

    public function bukti_gudangs() : HasMany {
        return $this->hasMany(BuktiGudang::class);
    }
}
