<?php

namespace Tests\Feature;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('diabsen++');
    }

    public function test_guru_can_login_using_nidn()
    {
        $user = User::create([
            'name' => 'Guru Budi',
            'username' => '19820315',
            'role' => 'guru',
            'password' => bcrypt('password'),
        ]);

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'nidn' => '19820315',
            'name' => 'Guru Budi',
            'qr_code_token' => 'QR_BUDI_1234',
        ]);

        $response = $this->post('/login', [
            'login_input' => '19820315',
            'password' => 'password',
        ]);

        $response->assertRedirect('/guru/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_tu_can_login_using_email()
    {
        $user = User::create([
            'name' => 'Staf TU',
            'email' => 'tu@diabsen.com',
            'role' => 'tu',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'login_input' => 'tu@diabsen.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/tu/scanner');
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_login_returns_errors()
    {
        $response = $this->post('/login', [
            'login_input' => 'nonexistent',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('login_input');
        $this->assertGuest();
    }
}
