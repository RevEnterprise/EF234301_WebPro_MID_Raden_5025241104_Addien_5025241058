<div class="max-w-2xl mx-auto p-6 bg-white border dark:bg-zinc-800 rounded-lg shadow">
    <h1 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-gray-100">{{ __('Create New Table') }}</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Table Name') }}</label>
            <input type="text" wire:model="name" id="name"
                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-4">
            <div class="w-1/2">
                <label for="row_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Rows') }}</label>
                <input type="number" wire:model="row_count" id="row_count"
                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                @error('row_count') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="w-1/2">
                <label for="column_count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Columns') }}</label>
                <input type="number" wire:model="column_count" id="column_count"
                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-zinc-600 dark:bg-zinc-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                @error('column_count') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <button type="submit"
            class="w-full bg-indigo-600 text-black px-4 py-2 rounded-md border dark:text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            {{ __('Create Table') }}
        </button>
    </form>
</div>
