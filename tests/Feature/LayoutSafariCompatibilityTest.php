<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LayoutSafariCompatibilityTest extends TestCase
{
    public function test_authenticated_layout_provides_csrf_token_for_notification_request(): void
    {
        $user = new User([
            'nama' => 'Safari Test',
            'email' => 'safari@test.com',
            'role' => 'Pemilik',
            'nomor_telepon' => '08123456789',
        ]);
        $user->id = 1;

        $view = $this->actingAs($user)->view('layout.template');

        $view->assertSee('name="csrf-token"', false);
        $view->assertSee(csrf_token(), false);
    }
}
