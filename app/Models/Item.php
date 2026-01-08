<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;

    public function itemPenawarans()
    {
        return $this->hasMany(ItemPenawaran::class);
    }

    public function jenis()
    {
        return $this->belongsTo(Jenis::class);
    }
}
