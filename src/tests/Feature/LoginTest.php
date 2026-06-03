<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_login_successfully()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'password123',
        ]);

        $response = $this->post(route('login.store'),[
            'email' => 'john@doe.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('home'));
    }

    #[Test]
    public function user_logout_successfully()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'password123',
        ]);

        $response = $this->actingAs($user)->get(route('logout'));
        $this->assertGuest();
        $response->assertRedirect(route('home'));
    }
}