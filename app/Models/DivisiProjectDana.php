<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisiProjectDana extends Model
{
    // Gunakan koneksi PostgreSQL
    protected $connection = 'pgsql';

    // Tentukan nama tabel
    protected $table = 'akuntansi.program_pusat';  // Contoh tabel PostgreSQL
}
