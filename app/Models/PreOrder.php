<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    /** @use HasFactory<\Database\Factories\PreOrderFactory> */
    use HasFactory;

    public function suplier()
    {
        return $this->belongsTo(Suplier::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function itemPenawarans()
    {
        return $this->hasMany(ItemPenawaran::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenis()
    {
        return $this->belongsTo(Jenis::class);
    }
}
