@props(['name','title'])

<div 
    x-data="{
        show: false,
        name: @js($name),
        trigger: null,
        open() {
            this.trigger = document.activeElement;
            this.show = true;
            this.$nextTick(() => this.$refs.closeButton.focus());
        },
        close() {
            this.show = false;
            this.$nextTick(() => this.trigger?.focus());
        }
    }"
    x-show="show"
    x-trap="show"
    x-transition:enter="duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @open-modal.window="if ($event.detail === name) open(); else close();"
    @click.self="close()"
    @keydown.escape.window="if (show) close()"
    class="fixed inset-0 bg-black/75 flex items-center justify-center"
    style="display: none;"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $name }}-title"
    >
    <x-card @click.stop class="shadow-xl max-w-2xl w-full max-h-[80dvh] overflow-auto mx-4">
        <div class="flex justify-between items-start gap-4">
            <h2 class="text-2xl font-bold" id="{{ $name }}-title">{{ $title }}</h2>
            <button x-ref="closeButton" type="button" class="btn btn-outlined h-8 px-3" aria-label="Close modal" @click="close()">×</button>
        </div>
        <div class="mt-6">
            {{ $slot }}
        </div>
    </x-card>
</div>