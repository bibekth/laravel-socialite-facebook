<x-layouts.app title="Dashboard">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:menu.radio.group>
        <div class="p-0 text-sm font-normal">
            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                    <span
                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                        <img src="https://graph.facebook.com/v18.0/me/picture?type=large&access_token={{ auth()->user()->token }}"
                            alt="">
                    </span>
                </span>

                <div class="grid flex-1 text-left text-sm leading-tight">
                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </flux:menu.radio.group>

    <form wire:submit="savePost">
        <flux:input wire:model="title" label="Title" description="The title of the post." />
    </form>
        {{-- <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div> --}}
        {{-- <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div> --}}
    </div>
</x-layouts.app>
