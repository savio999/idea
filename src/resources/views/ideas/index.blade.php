<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Your Ideas</h1>
            <p class="text-muted-foreground text-sm mt-2">
                Capture your thoughts and ideas here.
            </p>
            <x-card 
                x-data
                @click="$dispatch('open-modal', 'create-idea')"
                class="mt-10 space-y-3 cursor-pointer h-32 w-full text-left"
                is="button">
                <p>What is your idea?</p>
            </x-card>
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

        {{-- modal --}}
        <x-modal name="create-idea" title="New idea">
            <form 
                x-data="{status: 'pending', links: [], newLink: ''}" 
                action="{{ route('ideas.store') }}" 
                method="POST"
            >
                @csrf                
                <div class="space-y-6">
                    <x-form.field name="title" type="text" placeholder="Enter your idea title" autofocus required/>
                    <x-form.error name="title"/>
                    <x-form.field name="description" label="Description" type="textarea" placeholder="Enter your idea description" />
                    <x-form.error name="description"/>

                    <div class="space-y-2">
                        <label for="status">Status</label>
                        <div class="flex gap-x-3">
                            @foreach(\App\IdeaStatus::cases() as $status)
                                <button 
                                    type="button"
                                    class="btn flex-1 h-10"
                                    :class="status === @js($status->value) ? '' : 'btn-outlined'"
                                    :aria-pressed="status === @js($status->value)"
                                    @click="status = @js($status->value)"
                                >
                                    {{ $status->label() }}
                                </button>
                            @endforeach
                            <input type="hidden" name="status" x-model="status">
                        </div>
                        <x-form.error name="status"/>
                    </div>
                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Links</legend>
                            <template x-for="link in links">
                                <div class="flex gap-x-2 items-center">
                                    <input type="text" name="links[]" x-model="link" class="input">
                                    <button 
                                        type="button" 
                                        @click="links.splice(links.indexOf(link), 1)"
                                        aria-label="Remove link"
                                    >
                                        x
                                    </button>
                                </div>
                            </template>
                            <div class="flex gap-x-2 items-center">
                                <input 
                                    x-model="newLink"
                                    type="url" 
                                    placeholder="https://example.com" 
                                    autocomplete="off" 
                                    class="input flex-1" 
                                    spellcheck="false" 
                                />

                                <button 
                                    type="button" 
                                    @click="links.push(newLink.trim()); newLink = ''"
                                    :disabled="newLink.trim().length === 0"
                                >
                                    +
                                </button>
                            </div>
                        </fieldset>
                    </div>

                    <div class="flex justify-end gap-x-3 pt-2">
                        <button type="button" class="btn btn-outlined h-10" @click="close()">Cancel</button>
                        <button type="submit" class="btn h-10">Create</button>
                    </div>
                </div>
            </form>
        </x-modal>
    </div>
</x-layout>