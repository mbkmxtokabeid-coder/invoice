<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Invoice;
use App\Models\KategoriBarang;
use App\Models\Order;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\Invoice::factory()->create([
        //     'nama_invoice' => 'Ikhtiar Berkah Plakat',
        //     'kode_invoice' => 'IBPL'
        // ]);
        Invoice::factory()->create();
        User::factory()->create();
        Order::factory()->create();
        KategoriBarang::factory()->create();
        Barang::factory()->create();
    }
}
