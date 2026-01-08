<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Satuan extends Model
{
    /** @use HasFactory<\Database\Factories\SatuanFactory> */
    use HasFactory;

    public function itemPenawarans() : HasMany {
        return $this->hasMany(ItemPenawaran::class);
    }
}
