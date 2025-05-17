<x-layouts.app title="{{ $repo->full_name }} | Releases">
    <div class="flex h-full w-full max-w-4xl mx-auto flex-1 flex-col gap-4 rounded-xl">
        <livewire:index-releases :repository="$repo" />
    </div>
</x-layouts.app>
