<div>
    @if ($tables->isEmpty())
        <p class="text-black-600">You don’t have any tables yet.</p>
    @else
        @foreach ($tables as $table)
        <div class="flex hover:bg-gray-300">
            <flux:navlist.item :href="route('tables.show', $table)" :current="request()->routeIs('dashboard')" wire:navigate>{{ $table->name }}</flux:navlist.item>
            <button wire:click="deleteTable({{ $table->id }})" class="text-red-500 hover:bg-gray-300" title="Delete Table">X</button>
        </div>
        @endforeach
    @endif
</div>
