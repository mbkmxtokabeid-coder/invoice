<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'jenis_barang' => 'Trodat 1000 Std Line Dater',
            'kode_barang' => '1000',
            'kategori_id' => '1',
            'stok' => '10',
            'harga_modal' => '0',
            'harga_jual' => '70000',
        ];
    }
}
$stempel2 = Barang::factory()->create([
    'jenis_barang' => 'Trodat 1010 Std Line Dater',
    'kode_barang' => '1010',
    'kategori_id' => '1',
    'stok' => '15',
    'harga_modal' => '20000',
    'harga_jual' => '80000',
]);
$stempel3 = Barang::factory()->create([
    'jenis_barang' => 'Trodat 1014 Std Line Dater',
    'kode_barang' => '1014',
    'kategori_id' => '1',
    'stok' => '5',
    'harga_modal' => '0',
    'harga_jual' => '80000',
]);
$stempel4 = Barang::factory()->create([
    'jenis_barang' => 'Trodat 2210',
    'kode_barang' => '2210',
    'kategori_id' => '1',
    'stok' => '30',
    'harga_modal' => '50000',
    'harga_jual' => '100000',
]);
$souvenir1 = Barang::factory()->create([
    'jenis_barang' => 'Flashdisk 8 GB',
    'kode_barang' => 'FD8',
    'kategori_id' => '5',
    'stok' => '100',
    'harga_modal' => '24500',
    'harga_jual' => '60000',
]);
$souvenir2 = Barang::factory()->create([
    'jenis_barang' => 'Goodiebag Spunbond',
    'kode_barang' => 'IBBOX',
    'kategori_id' => '5',
    'stok' => '100',
    'harga_modal' => '0',
    'harga_jual' => '5000',
]);
$PLAKAT1 = Barang::factory()->create([
    'jenis_barang' => 'Plakat Akrilik 5mm Uk. 13x18cm',
    'kode_barang' => 'PA5',
    'kategori_id' => '4',
    'stok' => '100',
    'harga_modal' => '0',
    'harga_jual' => '0',
]);
$PLAKAT1 = Barang::factory()->create([
    'jenis_barang' => 'Plakat Akrilik 8mm Uk. 13x18cm',
    'kode_barang' => 'PA8',
    'kategori_id' => '4',
    'stok' => '100',
    'harga_modal' => '0',
    'harga_jual' => '0',
]);
$jasa = Barang::factory()->create([
    'jenis_barang' => 'Jasa',
    'kode_barang' => 'jasa',
    'kategori_id' => '6',
    'stok' => '999999999',
    'harga_modal' => '0',
    'harga_jual' => '0',
]);
$express = Barang::factory()->create([
    'jenis_barang' => 'Express',
    'kode_barang' => 'express',
    'kategori_id' => '6',
    'stok' => '999999999',
    'harga_modal' => '0',
    'harga_jual' => '60000',
]);
