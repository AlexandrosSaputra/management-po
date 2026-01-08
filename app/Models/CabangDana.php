<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabangDana extends Model
{
    // Gunakan koneksi PostgreSQL
    protected $connection = 'pgsql';

    // Tentukan nama tabel
    protected $table = 'dana.cabang';  // Contoh tabel PostgreSQL
}
