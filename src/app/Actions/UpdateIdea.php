<?php

namespace App\Actions;

use App\Models\Idea;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateIdea
{
    public function handle(Idea $idea, array $attributes): Idea
    {
        $data = collect($attributes)->only(['title', 'description', 'links', 'status'])->toArray();

        if ($attributes['image'] ?? false) {
            if ($idea->image_path) {
                Storage::disk('public')->delete($idea->image_path);
            }

            $imagePath = Storage::disk('public')->putFile('images', $attributes['image']);

            if ($imagePath === false) {
                throw new \RuntimeException('Failed to upload image');
            }

            $data['image_path'] = $imagePath;
        }

        return DB::transaction(function () use ($idea, $data, $attributes): Idea {
            $steps = collect($attributes['steps'] ?? [])
                ->map(function (array $step): array {
                    return [
                        'description' => trim($step['description']),
                        'completed' => (bool) $step['completed'],
                    ];
                })
                ->filter(fn (array $step): bool => $step['description'] !== '')
                ->values()
                ->all();

            $idea->update($data);
            $idea->steps()->delete();
            $idea->steps()->createMany($steps);

            return $idea;
        });
    }
}
