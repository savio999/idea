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

class EditIdeaTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_edit_idea_successfully(): void
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Idea',
            'description' => 'Test Description',
            'status' => IdeaStatus::InProgress->value,
            'links' => ['https://example.com', 'https://laracasts.com']
        ]);

        $idea->steps()->createMany([ 
            [
                'description' => 'Draft outline',
                'completed'=> false
            ],
            [
                'description' => 'Build prototype',
                'completed'=> false
            ]
        ]);

        $response = $this->actingAs($user)->patch(route('ideas.update', $idea), [
            'title' => 'Updated Test Idea',
            'description' => 'Updated Test Description',
            'status' => IdeaStatus::Completed->value,
            'links' => ['https://example.com', 'https://laracasts.com', 'https://google.com'],
            'steps' => [ 
                [
                    'description' => 'Draft outline',
                    'completed'=> true
                ],
                [
                    'description' => 'Build prototype',
                    'completed'=> true
                ],
                [
                    'description' => 'Review feedback',
                    'completed'=> false
                ]
            ]
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('ideas.show', $idea));
        $response->assertSessionHas('success', 'Idea updated successfully');  
        $this->assertDatabaseHas('ideas', [
            'id' => $idea->id,
            'title' => 'Updated Test Idea',
            'description' => 'Updated Test Description',
            'status' => IdeaStatus::class::Completed->value,
            'links' => json_encode(['https://example.com', 'https://laracasts.com', 'https://google.com']),
        ]);
        $expectedSteps = [
            ['description' => 'Draft outline', 'completed' => true],
            ['description' => 'Build prototype', 'completed' => true],
            ['description' => 'Review feedback', 'completed' => false],
        ];

        foreach ($expectedSteps as $step) {
            $this->assertDatabaseHas('steps', [
                'idea_id' => $idea->id,
                ...$step,
            ]);
        }
    }

    #[Test]
    public function replacing_an_idea_image_removes_the_previous_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $oldImagePath = 'images/old-idea.png';
        Storage::disk('public')->put($oldImagePath, 'old image');

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'image_path' => $oldImagePath,
        ]);

        $newImage = UploadedFile::fake()->createWithContent(
            'new-idea.png',
            base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAAAAAA6fptVAAAACklEQVR42mP8z8AABQMBgGk8E1QAAAAASUVORK5CYII=')
        );

        $response = $this->actingAs($user)->patch(route('ideas.update', $idea), [
            'title' => 'Updated Test Idea',
            'description' => 'Updated Test Description',
            'status' => IdeaStatus::Completed->value,
            'image' => $newImage,
        ]);

        $response->assertRedirect(route('ideas.show', $idea));

        $idea->refresh();
        $this->assertFalse(Storage::disk('public')->exists($oldImagePath));
        $this->assertTrue(Storage::disk('public')->exists($idea->image_path));
    }
}