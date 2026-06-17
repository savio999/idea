<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idea</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground">
    <x-layout.nav />
    <main class="max-w-7xl mx-auto px-6 py-10">
        @if (session('success'))
            <div
                role="alert"
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 3000)"
                x-show="show"
                x-transition
                class="mb-6 rounded-lg border bg-primary px-6 py-4 text-sm font-medium text-black"
            >
                {{ session('success') }}
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
