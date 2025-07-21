<x-layouts.app :title="$title ?? null">
    <flux:main>
        <div class="relative">
            @include('partials.main-content-headings')
            @include('partials.main-content-alerts')
            {{ $slot }}
        </div>
    </flux:main>
</x-layouts.app>
