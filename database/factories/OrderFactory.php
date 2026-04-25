<?php

namespace Database\Factories;

use App\Models\Order;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel=Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_by' => 'Tokopedia'
        ];
    }
}
$shopee = Order::factory()->create([
    'order_by' => 'Shopee'
]);
$walkIn = Order::factory()->create([
    'order_by' => 'Walk In'
]);
$WaOnline = Order::factory()->create([
    'order_by' => 'WhatsApp/Online'
]);
$sales = Order::factory()->create([
    'order_by' => 'Sales'
]);
