<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cabang extends Model
{
    /** @use HasFactory<\Database\Factories\CabangFactory> */
    use HasFactory;

    public function gudangs() : HasMany {
        return $this->hasMany(Gudang::class);
    }

    public function supliers() : HasMany {
        return $this->hasMany(Suplier::class);
    }

    public function users() : HasMany {
        return $this->hasMany(User::class);
    }
}
