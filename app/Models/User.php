<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $casts = [
        'status' => 'boolean',
        'password' => 'hashed',
    ];

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => filter_var($this->status, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function rules()
    {
        return [
            'status' => ['required', 'boolean'],
        ];
    }

    public function pre_orders(): HasMany
    {
        return $this->hasMany(PreOrder::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function kontraks(): HasMany
    {
        return $this->hasMany(Kontrak::class);
    }

    public function hargas(): HasMany
    {
        return $this->hasMany(Harga::class);
    }

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'status',
        'telepon',
        'id_cabang',
        'level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mutator to ensure passwords are hashed when set.
     */
    public function setPasswordAttribute($value)
    {
        if ($value === null) {
            $this->attributes['password'] = null;
            return;
        }

        if (\Illuminate\Support\Facades\Hash::needsRehash($value)) {
            $this->attributes['password'] = \Illuminate\Support\Facades\Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }
}
