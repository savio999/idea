<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIdeaRequest;
use App\Http\Requests\UpdateIdeaRequest;
use App\IdeaStatus;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIdeaRequest $request): \Illuminate\Http\RedirectResponse
    {
        Auth::user()->ideas()->create($request->validated());
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
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIdeaRequest $request, Idea $idea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        $idea->delete();

        return redirect()->route('ideas.index');
    }
}
