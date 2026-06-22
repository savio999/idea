<x-layout>
    <div class="py-8 max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('ideas.index') }}" class="btn btn-outlined">Back</a>
            <div class="flex gap-x-3 items-center">
                <a class="btn btn-outlined" href="{{ route('ideas.edit', $idea) }}">Edit</a>
                <form action="{{ route('ideas.destroy', $idea) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outlined">Delete</button>
                </form>
            </div>
        </div>
        <div class="mt-8 space-y-6">
            <h1 class="text-4xl font-bold">{{ $idea->title }}</h1>
            <div class="mt-2 flex gap-x-3 items-center">
                <x-status-label status="{{ $idea->status }}">
                    {{ $idea->status->label() }}
                </x-status-label>
                <span class="text-muted-foreground text-sm">
                    {{ $idea->created_at->diffForHumans() }}
                </span>
            </div>  
            @if($idea->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($idea->image_path))                
                <div class="rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}" class="w-full h-auto object-cover">                
                </div>    
              @endif      
            <x-card class="mt-6">
                <p class="text-foreground prose prose-invert max-w-none cursor-pointer">{{ $idea->description }}</p>
            </x-card>
            @if($idea->steps->count() > 0)
                <div>
                    <h3 class="font-bold text-xl mt-6">Steps</h3>
                    <div class="space-y-2">
                        @foreach($idea->steps as $step)
                            <x-card>
                            <form action="{{ route('step.update',['idea' => $idea, 'step' => $step]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="flex gap-x-3 items-center">
                                <button type="submit" role="switch" aria-checked="{{ $step->completed ? 'true' : 'false' }}" class="size-5 flex items-center justify-center rounded-lg text-primary-foreground border {{ $step->completed ? 'bg-primary border-primary' : 'border-primary' }}">&#10003;</button>
                                <span class="{{ $step->completed ? 'line-through text-muted-foreground' : '' }}">{{ $step->description }}</span>
                                </div>
                            </form>
                            </x-card>
                        @endforeach
                    </div>
                </div>
            @endif
            @if($idea->links->count() > 0)
                <div>
                    <h3 class="font-bold text-xl mt-6">links</h3>
                    <div class="space-y-2">
                        @foreach($idea->links as $link)
                            <x-card class="text-primary font-medium flex gap-x-3 items-center" href="{{ $link }}">{{ $link }}</x-card>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>