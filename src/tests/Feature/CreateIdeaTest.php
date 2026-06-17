<?php

namespace Tests\Feature;

use App\IdeaStatus;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateIdeaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_create_idea_successfully(): void
    {
        $user = User::factory()->create();
        $links = ['https://example.com', 'https://laracasts.com'];

        $response = $this->actingAs($user)
            ->post(route('ideas.store'), [
                'title' => 'Test Idea',
                'description' => 'Test Description',
                'status' => IdeaStatus::InProgress->value,
                'links' => $links,
            ]);

        $response->assertRedirect(route('ideas.index'));
        $response->assertSessionHas('success', 'Idea created successfully');

        $this->assertDatabaseHas('ideas', [
            'title' => 'Test Idea',
            'description' => 'Test Description',
            'status' => IdeaStatus::InProgress->value,
            'user_id' => $user->id,
        ]);

        $idea = Idea::where('title', 'Test Idea')->firstOrFail();

        $this->assertSame($links, $idea->links->getArrayCopy());
    }

}