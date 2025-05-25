<div class="space-y-3">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Repositories</h1>
        <div>
            {{ $this->count }}
            {{ Str::plural($this->search === '' ? 'repository' : 'match', $this->count) }}
        </div>
    </div>
    <div class="flex items-center gap-2">
        <flux:input
            wire:model.live.debounce.300ms="search"
            icon="magnifying-glass"
            placeholder="Search repositories"
            clearable
        />
        <flux:button
            icon="arrow-path"
            type="button"
            data-tooltip-target="tooltip-default"
            wire:click="refreshAll"
            wire:target="refreshAll"
            class="flex size-10 cursor-pointer items-center justify-center rounded-lg border border-zinc-200 border-b-zinc-300/80 bg-white text-zinc-700 shadow-xs disabled:border-b-zinc-200 disabled:text-zinc-500 disabled:shadow-none dark:border-white/10 dark:bg-white/10 dark:text-zinc-300 dark:shadow-none dark:disabled:border-white/5 dark:disabled:bg-white/[7%] dark:disabled:text-zinc-400"
        />
        <div
            id="tooltip-default"
            role="tooltip"
            class="tooltip invisible absolute z-10 inline-block rounded-lg bg-zinc-700 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-xs transition-opacity duration-300 dark:bg-zinc-900"
        >
            Refresh all repositories
            <div
                class="tooltip-arrow"
                data-popper-arrow
            ></div>
        </div>
    </div>

    <ul
        role="list"
        class="space-y-2 overflow-hidden"
    >
        @foreach ($repositories as $repository)
            <x-list-card wire:key="{{$repository->name}}">
                <div class="flex min-w-0 gap-x-4">
                    <div class="flex min-w-0 flex-col justify-center">
                        <p class="font-semibold text-gray-900 dark:text-gray-100">
                            <a href="{{ route('repositories.show', ['name' => $repository->name]) }}">
                                <span class="absolute inset-x-0 -top-px bottom-0"></span>
                                {{ $repository->name }}
                            </a>
                        </p>
                        <p class="mt-1 flex text-xs/5 text-gray-500">
                            <a
                                href="{{ $repository->url }}"
                                class="relative flex items-center gap-x-1 truncate hover:underline"
                            >
                                <svg
                                    class="size-4"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill="currentColor"
                                        d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5c.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34c-.46-1.16-1.11-1.47-1.11-1.47c-.91-.62.07-.6.07-.6c1 .07 1.53 1.03 1.53 1.03c.87 1.52 2.34 1.07 2.91.83c.09-.65.35-1.09.63-1.34c-2.22-.25-4.55-1.11-4.55-4.92c0-1.11.38-2 1.03-2.71c-.1-.25-.45-1.29.1-2.64c0 0 .84-.27 2.75 1.02c.79-.22 1.65-.33 2.5-.33s1.71.11 2.5.33c1.91-1.29 2.75-1.02 2.75-1.02c.55 1.35.2 2.39.1 2.64c.65.71 1.03 1.6 1.03 2.71c0 3.82-2.34 4.66-4.57 4.91c.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2"
                                    />
                                </svg>
                                {{ $repository->url }}
                            </a>
                        </p>
                    </div>
                </div>
                <div class="flex shrink-0 items-center gap-x-4">
                    <div class="hidden sm:flex sm:flex-col sm:items-end">
                        <div class="mt-1 flex items-center gap-2">
                            @if ($repository->latestRelease)
                                <div class="text-sm/5 text-gray-500">
                                    <span class="font-semibold">{{ $repository->latestRelease?->tag }}</span>
                                    published
                                    <time datetime="{{ $repository->latestRelease?->published_at?->toAtomString() }}">
                                        {{ $repository->latestRelease?->published_at?->diffForHumans() }}
                                    </time>
                                </div>
                            @endif

                            <div class="flex items-center text-sm/6 text-gray-900 dark:text-gray-100">
                                <span>{{ $repository->releases_count }} {{ Str::plural('release', $repository->releases_count) }}</span>
                            </div>
                        </div>
                        <div class="mt-1 text-xs/5 text-gray-500">
                            <div class="flex items-center gap-2">
                                @if ($repository->synced_at)
                                    <span>Synced {{ $repository->synced_at?->diffForHumans() }}</span>
                                @else
                                    <span>Never synced</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <flux:button
                        size="xs"
                        icon="arrow-path"
                        wire:click="syncRepository({{ $repository }})"
                    />
                </div>
            </x-list-card>
        @endforeach
    </ul>
    {{ $repositories->links() }}
</div>
