<div class="text-zinc-700 dark:text-zinc-300">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">{{ $repository->full_name }}</h1>
        <div>
            {{ $this->count }}
            {{ Str::plural($this->search === '' ? 'release':'match', $this->count) }}
        </div>
    </div>
    <div class="text-sm mb-4">{{$repository->description}}</div>

    <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" class="mb-4"
                placeholder="Search releases" clearable/>
    <ul role="list"
        class="overflow-hidden ">
        @foreach($releases as $release)
            <div x-data="{ expanded: {{request()->boolean('collapsed') ? 'false' : 'true'}} }" class="flex flex-col bg-white dark:bg-zinc-900 shadow-md ring-1 ring-gray-900/5 rounded-lg mb-2">
                <li class="relative flex justify-between gap-x-6 px-4 py-5 hover:bg-gray-50 dark:hover:bg-zinc-950 sm:px-6">
                    <div class="flex min-w-0 gap-x-4">
                        <div class="min-w-0 flex-auto">
                            <p class=" text-gray-900 dark:text-gray-100">
                            @if($release->body)
                                <div @click="expanded = !expanded" class="cursor-pointer">
                                    <span class="absolute inset-x-0 -top-px bottom-0"></span>
                                </div>
                            @endif
                            <span class="font-bold text-2xl">{{$release->tag}}</span>
                            </p>
                            <p class="mt-1 flex text-xs/5 text-gray-500">
                                <a href="{{$release->url}}"
                                   class="relative truncate hover:underline flex items-center gap-x-1">
                                    <svg class="size-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path fill="currentColor"
                                              d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5c.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34c-.46-1.16-1.11-1.47-1.11-1.47c-.91-.62.07-.6.07-.6c1 .07 1.53 1.03 1.53 1.03c.87 1.52 2.34 1.07 2.91.83c.09-.65.35-1.09.63-1.34c-2.22-.25-4.55-1.11-4.55-4.92c0-1.11.38-2 1.03-2.71c-.1-.25-.45-1.29.1-2.64c0 0 .84-.27 2.75 1.02c.79-.22 1.65-.33 2.5-.33s1.71.11 2.5.33c1.91-1.29 2.75-1.02 2.75-1.02c.55 1.35.2 2.39.1 2.64c.65.71 1.03 1.6 1.03 2.71c0 3.82-2.34 4.66-4.57 4.91c.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2"/>
                                    </svg>
                                    {{$release->url}}
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="flex shrink-0 items-center gap-x-4">
                        <div class="hidden sm:flex sm:flex-col sm:items-end">
                            <p class="text-sm/6 text-gray-900 dark:text-gray-100">
                                Released
                                <time datetime="{{ $release->published_at->toAtomString() }}">
                                    {{ $release->published_at?->diffForHumans() }}
                                </time>
                            </p>
                            @if(!$release->body)
                                <p class="mt-1 text-xs/5 text-gray-500">
                                    <span class="font-semibold">No release notes</span>
                                </p>
                            @endif
                        </div>
                        @if($release->body)
                            <svg class="size-5 flex-none text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                 aria-hidden="true" data-slot="icon">
                                <path fill-rule="evenodd"
                                      d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                      clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </div>
                </li>
                @if($release->body)
                    <li x-cloak x-show="expanded" x-collapse
                         class="min-w-full px-4 py-2 prose-a:text-zinc-800 dark:prose-a:text-zinc-400 prose dark:prose-invert prose-headings:mt-2 prose-h1:text-2xl prose-hr:my-2 border-t border-zinc-200 dark:border-zinc-700">
                        {!! $release->renderMarkdown($this->search) !!}
                    </li>
                @endif
            </div>
        @endforeach
    </ul>
    <div class="mt-4">
        {{ $releases->links() }}
    </div>
</div>
