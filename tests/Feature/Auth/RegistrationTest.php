<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->get(route('dashboard'))->assertOk();

        $user = User::where('email', 'test@example.com')->firstOrFail();
        $this->assertSame('Test User', $user->nama_lengkap);
        $this->assertSame('WORKER', $user->role);
        $this->assertTrue($user->hasRole('Worker'));
        $this->assertFalse($user->can('manage profile'));
    }
}
