<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-100/80 dark:bg-zinc-800">
        <flux:header
            container
            class="border-b border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900"
        >
            <flux:sidebar.toggle
                class="lg:hidden"
                icon="bars-2"
                inset="left"
            />

            <a
                href="{{ route('repositories.index') }}"
                class="ms-2 me-5 flex items-center space-x-2 lg:ms-0 rtl:space-x-reverse"
                wire:navigate
            >
                <x-app-logo />
            </a>

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 py-0! rtl:space-x-reverse">
                <flux:tooltip
                    :content="__('Toggle dark mode')"
                    position="bottom"
                >
                    <flux:button
                        x-data
                        x-on:click="$flux.dark = ! $flux.dark"
                        variant="subtle"
                        aria-label="Toggle dark mode"
                        class="!rounded-full !p-2.5"
                    >
                        <flux:icon.sun
                            x-cloak
                            x-show="$flux.appearance === 'light'"
                            variant="mini"
                        />
                        <flux:icon.moon
                            x-cloak
                            x-show="$flux.appearance !== 'light'"
                            variant="mini"
                        />
                    </flux:button>
                </flux:tooltip>
                <flux:tooltip
                    content="Add a new repository"
                    position="bottom"
                >
                    <flux:modal.trigger name="add-repository">
                        <flux:button
                            icon="plus"
                            variant="subtle"
                            aria-label="Add a new repository"
                            class="!rounded-full !p-2.5"
                        />
                    </flux:modal.trigger>
                </flux:tooltip>
            </flux:navbar>
            @auth
                <!-- Desktop User Menu -->
                <flux:dropdown
                    position="top"
                    align="end"
                >
                    <flux:profile
                        class="cursor-pointer"
                        :initials="auth()->user()->initials()"
                    />

                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item
                                :href="route('settings.profile')"
                                icon="cog"
                                wire:navigate
                            >
                                {{ __('Settings') }}
                            </flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                            class="w-full"
                        >
                            @csrf
                            <flux:menu.item
                                as="button"
                                type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="w-full"
                            >
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @endauth
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar
            stashable
            sticky
            class="border-e border-zinc-200 bg-zinc-50 lg:hidden dark:border-zinc-700 dark:bg-zinc-900"
        >
            <flux:sidebar.toggle
                class="lg:hidden"
                icon="x-mark"
            />

            <a
                href="{{ route('repositories.index') }}"
                class="ms-1 flex items-center space-x-2 rtl:space-x-reverse"
                wire:navigate
            >
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')">
                    <flux:navlist.item
                        icon="layout-grid"
                        :href="route('repositories.index')"
                        :current="request()->routeIs('repositories.index')"
                        wire:navigate
                    >
                        {{ __('Index') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline"></flux:navlist>
        </flux:sidebar>

        {{ $slot }}
        <livewire:add-repository-modal />
        <x-toaster-hub />

        @fluxScripts
    </body>
</html>
