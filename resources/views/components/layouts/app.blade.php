<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        <livewire:header />
        {{ $slot }}
    </flux:main>

    <livewire:footer />
</x-layouts.app.sidebar>
