<?php

namespace Tests\Feature;

use App\IdeaStatus;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShowIdeaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_view_his_idea(): void
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('ideas.show', $idea));

        $response->assertOk();
        $response->assertSee($idea->title);
    }

    #[Test]
    public function user_cannot_view_other_users_idea(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $idea = Idea::factory()->create([
            'user_id' => $user2->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('ideas.show', $idea));

        $response->assertForbidden();
    }

    #[Test]
    public function guest_users_cannot_view_ideas(): void
    {
        $idea = Idea::factory()->create();
        $response = $this->get(route('ideas.show', $idea));
        $response->assertRedirect(route('login'));
    }
}
