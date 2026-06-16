<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Your Ideas</h1>
            <p class="text-muted-foreground text-sm mt-2">
                Capture your thoughts and ideas here.
            </p>
        </header>
        <div>
            <a href="{{ route('ideas.index') }}" class="btn {{ request()->status == null ? '' : 'btn-outlined'}}">
                All<span class="text-xs pl-3">{{ $counts->get('All', 0)}}</span>
            </a>
            @foreach(\App\IdeaStatus::cases() as $status)
                <a href="{{ route('ideas.index',['status' => $status->value]) }}" 
                    class="btn {{ request()->status == $status->value ? '' : 'btn-outlined'}}">
                    {{ $status->label() }}<span class="text-xs pl-3">{{ $counts->get($status->value, 0)}}</span>
                </a>
            @endforeach
        </div>
        <div class="mt-10 text-muted-foreground">
            <div class="grid md:grid-cols-2 gap-6">
                @forelse($ideas as $idea)
                    <x-card href="{{ route('ideas.show', $idea) }}">
                        <h3 class="text-foreground text-lg">{{ $idea->title }}</h3>
                        <x-status-label status="{{ $idea->status }}">
                            {{ $idea->status->label() }}
                        </x-status-label>
                        <div class="mt-5 line-clamp-3">{{ $idea->description }}</div>
                        <div class="mt-4">{{ $idea->created_at->diffForHumans() }}</div>
                    </x-card>
                @empty
                <x-card>
                    <p class="text-muted-foreground">No ideas found.</p>
                </x-card>
                @endforelse
            </div>
        </div>
    </div>
</x-layout>