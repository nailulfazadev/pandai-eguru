<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guests are redirected to login page.
     */
    public function test_guests_are_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test login page is accessible to guests.
     */
    public function test_login_page_is_accessible_to_guests(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test login succeeds with correct credentials.
     */
    public function test_login_succeeds_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'guru@pandai.com',
            'password' => bcrypt('08123456789'),
        ]);

        $response = $this->post('/login', [
            'email' => 'guru@pandai.com',
            'password' => '08123456789',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test login fails with incorrect credentials.
     */
    public function test_login_fails_with_incorrect_credentials(): void
    {
        User::factory()->create([
            'email' => 'guru@pandai.com',
            'password' => bcrypt('08123456789'),
        ]);

        $response = $this->post('/login', [
            'email' => 'guru@pandai.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test logout redirects to login.
     */
    public function test_logout_redirects_to_login(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
