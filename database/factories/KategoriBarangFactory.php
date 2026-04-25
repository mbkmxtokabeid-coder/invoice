<?php

namespace Database\Factories;

use App\Models\KategoriBarang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KategoriBarang>
 */
class KategoriBarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kategori' => 'Souvenir'
        ];
    }
}
$stempel = KategoriBarang::factory()->create([
    'nama_kategori' => 'Stempel'
]);

$digitalPrint = KategoriBarang::factory()->create([
    'nama_kategori' => 'Digital Printing'
]);

$tumbler = KategoriBarang::factory()->create([
    'nama_kategori' => 'Tumbler'
]);
$plakat = KategoriBarang::factory()->create([
    'nama_kategori' => 'Plakat'
]);
$lain = KategoriBarang::factory()->create([
    'nama_kategori' => 'Lainnya'
]);
