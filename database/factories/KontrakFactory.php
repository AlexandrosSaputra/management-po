<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kontrak>
 */
class KontrakFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'suplier_id' => rand(1, 10),
            // 'user_id' => rand(1, 2),
            // 'jenis_id' => rand(1, 10),
            // 'total_biaya' => strval(rand(10000, 100000)),
            // 'token' => Str::random(40),
            // 'tanggal_mulai' => date('Y-m-d'),
            // 'tanggal_akhir' => date('Y-m-d'),
        ];
    }
}
