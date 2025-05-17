<div>
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-semibold">Repositories</h1>
        <div>
            {{ $this->count }}
            {{ Str::plural($this->search === '' ? 'repository':'match', $this->count) }}
        </div>
    </div>
    <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" class="mb-4"
                placeholder="Search repositories" clearable/>
    <ul role="list"
        class="overflow-hidden ">
        @foreach($repositories as $repository)
            <li class="relative flex justify-between gap-x-6 px-4 py-5 hover:bg-gray-100 dark:hover:bg-zinc-950 sm:px-6 bg-gray-50 dark:bg-zinc-900 shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl mb-4">
                <div class="flex min-w-0 gap-x-4">
                    <div class="min-w-0 flex-auto">
                        <p class=" font-semibold text-gray-900 dark:text-gray-100">
                            <a href="{{route('repositories.show', $repository->only('organization', 'repository'))}}">
                                <span class="absolute inset-x-0 -top-px bottom-0"></span>
                                {{$repository->full_name}}
                            </a>
                        </p>
                        <p class="mt-1 flex text-xs/5 text-gray-500">
                            <a href="{{$repository->url}}"
                               class="relative truncate hover:underline flex items-center gap-x-1">
                                <svg class="size-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor"
                                          d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5c.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34c-.46-1.16-1.11-1.47-1.11-1.47c-.91-.62.07-.6.07-.6c1 .07 1.53 1.03 1.53 1.03c.87 1.52 2.34 1.07 2.91.83c.09-.65.35-1.09.63-1.34c-2.22-.25-4.55-1.11-4.55-4.92c0-1.11.38-2 1.03-2.71c-.1-.25-.45-1.29.1-2.64c0 0 .84-.27 2.75 1.02c.79-.22 1.65-.33 2.5-.33s1.71.11 2.5.33c1.91-1.29 2.75-1.02 2.75-1.02c.55 1.35.2 2.39.1 2.64c.65.71 1.03 1.6 1.03 2.71c0 3.82-2.34 4.66-4.57 4.91c.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2"/>
                                </svg>
                                {{$repository->url}}
                            </a>
                        </p>
                    </div>
                </div>
                <div class="flex shrink-0 items-center gap-x-4">
                    <div class="hidden sm:flex sm:flex-col sm:items-end">
                        <p class="text-sm/6 text-gray-900 dark:text-gray-100">
                            {{$repository->releases_count}} {{ Str::plural('release', $repository->releases_count) }}
                            ⭐ {{Number::format($repository->stars)}}
                        </p>
                        <p class="mt-1 text-xs/5 text-gray-500"><span
                                class="font-semibold">{{$repository->latestRelease?->tag}}</span>
                            published
                            <time datetime="{{ $repository->latestRelease?->published_at?->toAtomString() }}">
                                {{ $repository->latestRelease?->published_at?->diffForHumans() }}
                            </time>
                        </p>
                    </div>
                    <svg class="size-5 flex-none text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                         aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd"
                              d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
            </li>
        @endforeach
    </ul>
    {{ $repositories->links() }}
</div>
