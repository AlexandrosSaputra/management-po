<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TemplateOrder>
 */
class TemplateOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'token' => Str::random(40),
            'user_id' => 1,
            'jenis_id' => rand(1, 3),
            'suplier_id' => 1,
            'gudang_id' => 1,
        ];
    }
}
