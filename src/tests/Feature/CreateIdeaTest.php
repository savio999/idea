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

class CreateIdeaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_create_idea_successfully(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $links = ['https://example.com', 'https://laracasts.com'];
        $steps = ['Draft outline', 'Build prototype', 'Review feedback'];
        $image = UploadedFile::fake()->createWithContent(
            'idea.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAAAAAA6fptVAAAACklEQVR42mP8z8AABQMBgGk8E1QAAAAASUVORK5CYII=')
        );

        $response = $this->actingAs($user)
            ->post(route('ideas.store'), [
                'title' => 'Test Idea',
                'description' => 'Test Description',
                'status' => IdeaStatus::InProgress->value,
                'links' => $links,
                'steps' => $steps,
                'image' => $image,
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
        $this->assertEqualsCanonicalizing($steps, $idea->steps()->pluck('description')->all());
        $this->assertIsString($idea->image_path);
        $this->assertTrue(Storage::disk('public')->exists($idea->image_path));
    }
}
