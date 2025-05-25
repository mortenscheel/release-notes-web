<x-layouts.app.header :title="$title ?? null">
    <flux:main>
        <div class="mx-auto flex h-full w-full max-w-4xl flex-1 flex-col gap-4 rounded-xl">
            {{ $slot }}
        </div>
    </flux:main>
</x-layouts.app.header>
