<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipePembayaran extends Model
{
    /** @use HasFactory<\Database\Factories\TipePembayaranFactory> */
    use HasFactory;

    protected $casts = [
        'isAktif' => 'boolean',
    ];

    protected function prepareForValidation()
    {
        $this->merge([
            'isAktif' => filter_var($this->isAktif, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function rules()
    {
        return [
            'isAktif' => ['required', 'boolean'],
        ];
    }


    public function arsipPembayarans(): HasMany
    {
        return $this->hasMany(ArsipPembayaran::class);
    }
}
