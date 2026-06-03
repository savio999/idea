<x-layout>
    @if (session('success'))
    <div 
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 3000)"
    x-show="show"
    x-transition.opacity.duration.1000ms
    class="bg-primary text-primary-foreground p-4 absolute bottom-4 right-4 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <p>Welcome to Idea</p>
</x-layout>