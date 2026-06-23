<?php

namespace App\Http\Controllers;

use App\Actions\CreateIdea;
use App\Actions\UpdateIdea;
use App\Http\Requests\IdeaRequest;
use App\IdeaStatus;
use App\Models\Idea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ideas = Idea::query()
            ->where('user_id', Auth::id())
            ->when($request->filled('status'), function ($query) use ($request) {
                $status = IdeaStatus::tryFrom($request->status);

                return $status ? $query->where('status', $status) : $query;
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $counts = Idea::statusCount(Auth::user());

        return view('ideas.index', compact('ideas', 'counts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IdeaRequest $request, CreateIdea $createIdea): RedirectResponse
    {
        $createIdea->handle($request->validated());

        return redirect()->route('ideas.index')->with('success', 'Idea created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea)
    {
        return view('ideas.show', compact('idea'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IdeaRequest $request, Idea $idea, UpdateIdea $updateIdea): RedirectResponse
    {
        $updateIdea->handle($idea, $request->validated());
        return redirect()->route('ideas.show', $idea)->with('success', 'Idea updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        $idea->delete();
        return redirect()->route('ideas.index');
    }

    public function destroyImage(Idea $idea): RedirectResponse
    {
        if ($idea->image_path) {
            $deleted = Storage::disk('public')->delete($idea->image_path);

            if (! $deleted) {
                Log::error('Failed to delete idea image.', [
                    'idea_id' => $idea->id,
                    'image_path' => $idea->image_path,
                ]);

                return redirect()->route('ideas.show', $idea)
                    ->withErrors(['image' => 'Image could not be removed. Please try again.']);
            }
        }

        $idea->update(['image_path' => null]);

        return redirect()->route('ideas.show', $idea)->with('success', 'Image removed successfully');
    }
}
