<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuktiGudang extends Model
{
    /** @use HasFactory<\Database\Factories\BuktiGudangFactory> */
    use HasFactory;

    public function itemPenawaran() : BelongsTo {
        return $this->belongsTo(ItemPenawaran::class);
    }
}
