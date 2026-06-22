<?php

namespace App\Actions;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateIdea
{
    public function __construct(#[CurrentUser()]private readonly User $user) {}
    /**
     * Create a new idea
     *
     * @param array $attributes
     * @return Idea
     * throw an exception if the idea is not created
     */
    public function handle(array $attributes): Idea
    {
        $data = collect($attributes)->only(['title', 'description', 'links', 'status'])->toArray();

        if ($attributes['image'] ?? false) {
            $imagePath = Storage::disk('public')->putFile('images', $attributes['image']);

            if ($imagePath === false) {
                throw new \RuntimeException('Failed to upload image');
            }

            $data['image_path'] = $imagePath;
        }

        return DB::transaction(function () use ($data, $attributes): Idea {
            $steps = collect($attributes['steps'] ?? [])
                ->map(fn (string $step): string => trim($step))
                ->filter(fn (string $step): bool => $step !== '')
                ->map(function (string $step): array {
                    return [
                        'description' => $step,
                        'completed' => false,
                    ];
                })
                ->values()
                ->all();

            $idea = $this->user->ideas()->create($data);
            $idea->steps()->createMany($steps);
            $idea->save();

            return $idea;
        });
    }
}
