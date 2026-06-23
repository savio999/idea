<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EditProfileTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function edit_requires_authentication()
    {
        $response = $this->get(route('profile.edit'));
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function registered_user_can_edit_profile()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->actingAs($user)->get(route('profile.edit'))
            ->assertSeeHtml('value="John Doe"')
            ->assertSeeHtml('value="john@example.com"');

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success', 'Your profile has been updated successfully');

        $this->assertEquals('Jane Doe', $user->fresh()->name);
        $this->assertEquals('jane@example.com', $user->fresh()->email);
        $this->assertTrue(Hash::check('password123', $user->fresh()->password));
    }
}