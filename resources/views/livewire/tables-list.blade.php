<div class="p-2">

    <div class="mb-3">
        <input
            type="text"
            wire:model.debounce.100ms="search"
            wire:keydown.enter="loadTables"
            placeholder="Search tables..."
            class="w-full rounded-md border border-gray-300 px-2 py-1 text-sm
                   dark:bg-zinc-800 dark:text-white dark:border-zinc-700
                   focus:outline-none focus:ring-1 focus:ring-indigo-500"
        />
    </div>

    <div class=" mb-3">
        <button wire:click="sortBy('name')" class="rounded-md border border-gray-300 px-2 py-1 text-sm
                   dark:bg-zinc-800 dark:text-white dark:border-zinc-700
                   focus:outline-none focus:ring-1 focus:ring-indigo-500">
            Name {{ $sortField === 'name' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' }}
        </button>

        <button wire:click="sortBy('created_at')" class="rounded-md border border-gray-300 px-2 py-1 text-sm
                   dark:bg-zinc-800 dark:text-white dark:border-zinc-700
                   focus:outline-none focus:ring-1 focus:ring-indigo-500">
            Date {{ $sortField === 'created_at' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' }}
        </button>
    </div>
    <div class="text-sm text-gray-500 mb-2">
        Sorting by: <strong>{{ $sortField }}</strong> ({{ $sortDirection }})
    </div>


    @if ($tables->isEmpty())
        <p class="text-gray-600 dark:text-gray-400 text-sm">No tables found.</p>
    @else
        @foreach ($tables as $table)
            <div class="flex items-center justify-between hover:bg-gray-200 dark:hover:bg-zinc-700 rounded px-2 py-1">
                <flux:navlist.item
                    :href="route('tables.show', $table)"
                    :current="request()->routeIs('tables.show', $table)"
                    wire:navigate
                >
                    {{ $table->name }}
                </flux:navlist.item>

                <button
                    wire:click="deleteTable({{ $table->id }})"
                    class="text-red-500 hover:text-red-700"
                    title="Delete Table"
                >
                    ✕
                </button>
            </div>
        @endforeach
    @endif
</div>

