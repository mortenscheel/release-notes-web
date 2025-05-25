<div
    role="status"
    id="toaster"
    x-data="toasterHub(@js($toasts), @js($config))"
    @class([
        "pointer-events-none fixed z-50 flex w-full flex-col p-4 sm:p-6",
        "bottom-0" => $alignment->is("bottom"),
        "top-1/2 -translate-y-1/2" => $alignment->is("middle"),
        "top-0" => $alignment->is("top"),
        "items-start rtl:items-end" => $position->is("left"),
        "items-center" => $position->is("center"),
        "items-end rtl:items-start" => $position->is("right"),
    ])
>
    <template
        x-for="toast in toasts"
        :key="toast.id"
    >
        <div
            x-show="toast.isVisible"
            x-init="$nextTick(() => toast.show($el))"
            @if ($alignment->is("bottom"))
                x-transition:enter-start="translate-y-12 opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
            @elseif ($alignment->is("top"))
                x-transition:enter-start="-translate-y-12 opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
            @else
                x-transition:enter-start="scale-90 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
            @endif
            x-transition:leave-end="scale-90 opacity-0"
            @class(["pointer-events-auto relative flex w-full max-w-xs transform items-center justify-between transition duration-300 ease-in-out", "text-center" => $position->is("center"), "mt-3" => $alignment->is("bottom"), "mb-3" => ! $alignment->is("bottom")])
            :class="toast.select({ error: 'text-white bg-red-500', info: 'text-black bg-gray-200', success: 'text-white bg-green-600', warning: 'text-white bg-orange-500' })"
        >
            <i
                x-text="toast.message"
                class="inline-block w-full rounded rounded-sm px-6 py-3 text-sm not-italic shadow-lg select-none"
            ></i>

            @if ($closeable)
                <button
                    @click="toast.dispose()"
                    aria-label="@lang("close")"
                    class="{{ $alignment->is("bottom") ? "top-3" : "top-0" }} p-2 focus:outline-hidden focus:outline-none rtl:right-auto rtl:left-0"
                >
                    <svg
                        class="h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        ></path>
                    </svg>
                </button>
            @endif
        </div>
    </template>
</div>
