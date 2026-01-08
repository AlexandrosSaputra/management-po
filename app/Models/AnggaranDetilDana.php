<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggaranDetilDana extends Model
{
    // Gunakan koneksi PostgreSQL
    protected $connection = 'pgsql';

    // Tentukan nama tabel
    protected $table = 'dana.anggaran_detil';  // Contoh tabel PostgreSQL

    protected $guarded = [];
}
