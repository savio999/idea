<x-layout>
    <div class="py-8 max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('ideas.index') }}" class="btn btn-outlined">Back</a>
            <div class="flex gap-x-3 items-center">
                <a class="btn btn-outlined" href="javascript:void(0)" @click="$dispatch('open-modal', 'edit-idea')" x-data>Edit</a>
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
            {{-- modal --}}
        <x-modal name="edit-idea" title="Edit idea">
            <form 
                x-data="{
                    status: @js(old('status', $idea->status->value)),
                    links: @js(old('links', $idea->links->getArrayCopy())),
                    steps: @js(old('steps', $idea->steps->toArray())),
                    newLink: '',
                    newStep: '',
                    addStep() {
                        if (this.newStep.trim().length === 0) {
                            return;
                        }

                        this.steps.push({
                            description: this.newStep.trim(),
                            completed: false,
                        });
                        this.newStep = '';
                    },
                    addLink() {
                        if (this.newLink.trim().length === 0) {
                            return;
                        }

                        this.links.push(this.newLink.trim());
                        this.newLink = '';
                    },
                }" 
                @if($errors->any())
                    x-init="$nextTick(() => $dispatch('open-modal', 'edit-idea'))"
                @endif
                action="{{ route('ideas.update', $idea) }}" 
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf                
                @method('PATCH')
                <div class="space-y-6">
                    <x-form.field name="title" type="text" placeholder="Enter your idea title"  value="{{ old('title', $idea->title) }}" autofocus required/>
                    <x-form.error name="title"/>
                    <x-form.field name="description" label="Description" type="textarea" placeholder="Enter your idea description" textValue="{{ old('description', $idea->description) }}" />
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
                    @if($idea->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($idea->image_path))                
                        <div class="rounded-lg overflow-hidden">
                            <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}" class="w-full h-44 object-cover">      
                            <button type="submit" class="btn btn-outlined h-10 mt-2 w-full" form="remove-image-form">Remove Image</button>
                        </div>    
                    @endif      
                    <div class="space-y-2">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image" class="file-input" accept="image/*">
                        <x-form.error name="image"/>
                    </div>

                    <div>
                        <label class="label">Steps</label>
                        <template x-for="(step, index) in steps" :key="index">
                            <div class="flex gap-x-2 items-center">                                    
                                <input type="text" :name="`steps[${index}][description]`" x-model="step.description" class="input" readonly>
                                <input type="hidden" :name="`steps[${index}][completed]`" :value="step.completed ? '1' : '0'">
                                <button 
                                    type="button" 
                                    @click="steps.splice(index, 1)"
                                    aria-label="Remove step"
                                >
                                    x
                                </button>
                            </div>
                        </template>
                        <div class="flex gap-x-2 items-center">
                            <input 
                                x-model="newStep"
                                placeholder="Enter a step" 
                                autocomplete="off" 
                                class="input flex-1" 
                                spellcheck="false" 
                                @keydown.enter.prevent="addStep()"
                            />

                            <button 
                                type="button" 
                                @click="addStep()"
                                :disabled="newStep.trim().length === 0"
                            >
                                +
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="label">Links</label>
                        <template x-for="(link, index) in links" :key="index">
                            <div class="flex gap-x-2 items-center">                                    
                                <input type="url" name="links[]" x-model="links[index]" class="input" readonly>
                                <button 
                                    type="button" 
                                    @click="links.splice(index, 1)"
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
                                @keydown.enter.prevent="addLink()"
                            />

                            <button 
                                type="button" 
                                @click="addLink()"
                                :disabled="newLink.trim().length === 0"
                            >
                                +
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end gap-x-3 pt-2">
                        <button type="button" class="btn btn-outlined h-10" @click="close()">Cancel</button>
                        <button type="submit" class="btn h-10">Update</button>
                    </div>
                </div>
            </form>
            <form action="{{ route('ideas.destroyImage', $idea) }}" method="POST" id="remove-image-form">
                @csrf
                @method('DELETE')                
            </form>
        </x-modal>
</x-layout>
