<x-layouts::app.sidebar :title="$title ?? null">
    <link rel="stylesheet" href="/css/filament/filament/app.css">
    <flux:main>
        @include('partials.session-messages')
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>