<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // 'nama' => 'Admin',
            // 'email' => 'admin@gmail.com',
            // 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            // 'nomor_telepon' => '085696786969',
            // 'role' => 'Admin',
            'nama' => 'Oky Irawan',
            'email' => 'oky@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'status' => 'Aktif',
            'nomor_telepon' => '081264009879',
            'role' => 'Pemilik',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
$admin1 = User::factory()->create([
    'nama' => 'Fauzah Namira',
    'email' => 'namira@gmail.com',
    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'status' => 'Aktif',
    'nomor_telepon' => '087869696869',
    'role' => 'Admin',
]);
$admin2 = User::factory()->create([
    'nama' => 'Nabila Utami',
    'email' => 'NABILA@gmail.com',
    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'status' => 'Aktif',
    'nomor_telepon' => '085363696969',
    'role' => 'Admin',
]);
$admin3 = User::factory()->create([
    'nama' => 'Khairun Nisa',
    'email' => 'nisa@gmail.com',
    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'status' => 'Aktif',
    'nomor_telepon' => '081178696969',
    'role' => 'Admin',
]);
