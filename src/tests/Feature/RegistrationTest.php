<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_register_successfully()
    {
        $response = $this->post(route('register.store'),[
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('users',[
            'name' => 'John Doe',
            'email' => 'john@doe.com'
        ]);

        $user = User::where('email','john@doe.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function user_cannot_register_with_invalid_data()
    {
        $response = $this->post(route("register.store"),[
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
        ]);

        $response->assertInvalid(['name','email','password']);
        $this->assertGuest();
    }
}