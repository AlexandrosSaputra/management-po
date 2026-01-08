<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jenis extends Model
{
    /** @use HasFactory<\Database\Factories\JenisFactory> */
    use HasFactory;

    public function orders() : HasMany {
        return $this->hasMany(Order::class);
    }

    public function items() : HasMany {
        return $this->hasMany(Item::class);
    }

    public function kontraks() : HasMany {
        return $this->hasMany(Kontrak::class);
    }

    public function pre_orders() : HasMany {
        return $this->hasMany(PreOrder::class);
    }

    public function template_orders() : HasMany {
        return $this->hasMany(TemplateOrder::class);
    }
}
