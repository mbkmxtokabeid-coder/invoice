<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Invoice;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => '1',
            'nama_invoice' => 'Ikhtiar Berkah Plakat',
            'kode_invoice' => 'IBPL'
        ];
    }
}
$ib = Invoice::factory()->create([
    'id' => '2',
    'nama_invoice' => 'Ikhtiar Berkah',
    'kode_invoice' => 'IB'
]);
$ibtr = Invoice::factory()->create([
    'id' => '3',
    'nama_invoice' => 'Ikhtiar Berkah Stempel',
    'kode_invoice' => 'IBTR',
]);
$ibs = Invoice::factory()->create([
    'id' => '4',
    'nama_invoice' => 'Ikhtiar Berkah Souvenir',
    'kode_invoice' => 'IBS'
]);
$tkb = Invoice::factory()->create([
    'id' => '5',
    'nama_invoice' => 'TKB Pajak',
    'kode_invoice' => 'TKBP'
]);
