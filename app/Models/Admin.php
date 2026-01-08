<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory;

    public function penawarans() {
        return $this->hasMany(ItemPenawaran::class);
    }

    public function pre_orders() {
        return $this->hasMany(PreOrder::class);
    }
}
