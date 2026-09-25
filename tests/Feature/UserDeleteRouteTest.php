<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class UserDeleteRouteTest extends TestCase
{
    public function test_user_delete_route_is_registered(): void
    {
        $this->assertTrue(Route::has('user.delete'), 'Route user.delete must be registered');

        $route = Route::getRoutes()->getByName('user.delete');
        $this->assertNotNull($route);
        $this->assertContains('DELETE', $route->methods());
        $this->assertEquals('delete-user/{id}', $route->uri());
        $this->assertContains('pemilik', $route->middleware());
    }

    public function test_guest_cannot_access_delete_user(): void
    {
        $response = $this->delete('/delete-user/99999');
        $response->assertRedirect('/login');
    }

    public function test_non_pemilik_cannot_delete_user(): void
    {
        $user = new User([
            'nama' => 'Admin Test',
            'email' => 'admin@test.com',
            'role' => 'Admin',
            'status' => 'Aktif',
        ]);
        $user->id = 998;
        $user->setAttribute('status', 'Aktif');

        $response = $this->actingAs($user)->delete('/delete-user/99999');
        $response->assertRedirect('/');
    }

    public function test_view_renders_hapus_karyawan_button_for_pemilik(): void
    {
        $pemilik = new User([
            'nama' => 'Pemilik Test',
            'email' => 'pemilik@test.com',
            'role' => 'Pemilik',
            'status' => 'Aktif',
        ]);
        $pemilik->id = 1;
        $pemilik->setAttribute('status', 'Aktif');

        $otherUser = new User([
            'nama' => 'Staff 1',
            'email' => 'staff1@test.com',
            'role' => 'Admin',
            'status' => 'Aktif',
            'nomor_telepon' => '08123456789',
        ]);
        $otherUser->id = 2;
        $otherUser->setAttribute('status', 'Aktif');

        $view = $this->actingAs($pemilik)->view('pages.user.daftar-user', [
            'users' => collect([$otherUser]),
        ]);

        $view->assertSee('Hapus Karyawan');
        $view->assertSee('deleteModal');
        $view->assertSee('hapus-btn');
        $view->assertSee('/delete-user/2');
    }
}
