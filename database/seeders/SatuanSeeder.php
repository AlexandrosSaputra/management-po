<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $satuan = ['kg', 'gr', 'pack', 'lusin'];

        foreach ($satuan as $namaSatuan) {
            Satuan::factory()->create([
                'nama' => $namaSatuan
            ]);
        }
    }
}
