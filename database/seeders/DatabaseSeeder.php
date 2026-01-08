<?php

namespace Database\Seeders;

use App\Models\Gudang;
use App\Models\Item;
use App\Models\Jenis;
use App\Models\Kontrak;
use App\Models\Suplier;
use App\Models\TemplateOrder;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Suplier::factory()->create();

        Item::factory(10)->create();

        Gudang::factory()->create();

        Jenis::factory(10)->create();
        $kode = ['BU', 'PP', 'PD'];
        $nama = ['Bumbu', 'Perlengakapan Penjualan', 'Perlengkapan Dapur'];
        foreach($kode as $index => $item ) {
            Jenis::factory()->create([
                'kode' => $item,
                'nama' => $nama[$index],
            ]);
        }

        // User::factory(2)->create();

        // Kontrak::factory(5)->create();

        // TemplateOrder::factory(10)->create();

        $this->call(SatuanSeeder::class);
    }
}
