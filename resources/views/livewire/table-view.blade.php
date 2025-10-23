<div
    class="max-w-6xl h-dvh border overflow-y-auto overflow-x-auto mx-auto p-6 bg-white dark:bg-zinc-800 rounded-lg shadow relative"
    x-data="tableInteraction()"
    @click.away="hideMenu()"
>
    <div class="flex items-center justify-between mb-4">
        @if ($isRenaming)
            <div class="flex items-center space-x-2">
                <input
                    type="text"
                    wire:model.defer="newName"
                    wire:keydown.enter="saveRename"
                    class="border-gray-300 dark:border-zinc-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 dark:text-gray-100 dark:bg-zinc-700"
                />
                <button wire:click="saveRename" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">Save</button>
                <button wire:click="cancelRename" class="px-2 py-1 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
            </div>
        @else
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                <span>{{ $table->name }}</span>
                <button wire:click="startRename" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 ml-2" title="Rename">
                    ✏️
                </button>
            </h1>
        @endif
    </div>

    <div class="text-gray-800 dark:text-gray-200 mb-6">
        <div><strong>Created:</strong> {{ $table->created_at->format('d-m-Y H:i') }}</div>
        <div><strong>Rows:</strong> {{ $table->row_count }}</div>
        <div><strong>Columns:</strong> {{ $table->column_count }}</div>
    </div>

    <div class="flex-1 h-full border border-gray-300 dark:border-zinc-700 rounded-lg overflow-auto">
        <table class="w-full h-full border-collapse table-fixed select-none">
            <tbody>
                @for ($row = 1; $row <= $table->row_count; $row++)
                    <tr>
                        @for ($col = 1; $col <= $table->column_count; $col++)
                            <td
                                x-ref="cell-{{ $row }}-{{ $col }}"
                                class="border border-gray-300 dark:border-zinc-700 text-sm text-gray-800 dark:text-gray-100 cursor-pointer align-middle text-left whitespace-normal break-words px-3 py-2 min-w-[8rem] max-w-[16rem] transition-colors duration-150 ease-in-out hover:bg-gray-50 dark:hover:bg-zinc-800/60"
                                :class="isSelected({{ $row }}, {{ $col }}) ? 'bg-blue-100 dark:bg-blue-800/50' : ''"
                                @click="editCell({{ $row }}, {{ $col }}, $refs['cell-{{ $row }}-{{ $col }}'])"
                                @contextmenu.prevent="openMenu({{ $row }}, {{ $col }}, $refs['cell-{{ $row }}-{{ $col }}'])"
                            >
                                <template x-if="editing.row === {{ $row }} && editing.col === {{ $col }}">
                                    <input
                                        type="text"
                                        x-model="editing.value"
                                        @blur="saveCell()"
                                        @keydown.enter.prevent="saveCell()"
                                        class="bg-transparent focus:outline-none text-gray-900 dark:text-gray-100 text-left w-full break-words whitespace-normal"
                                        autofocus
                                    />
                                </template>

                                <template x-if="!(editing.row === {{ $row }} && editing.col === {{ $col }})">
                                    <span class="inline-block w-full break-words whitespace-normal overflow-hidden">
                                        {{ $cells[$row][$col] ?? '' }}
                                    </span>
                                </template>
                            </td>
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <div
        x-show="menuVisible"
        x-transition
        class="absolute bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-600 rounded shadow-md text-sm text-gray-800 dark:text-gray-200 z-50"
        :style="menuStyle"
    >
        <ul class="divide-y divide-gray-200 dark:divide-zinc-600">
            <li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-600 cursor-pointer"
                @click="$wire.insertRow(selected.row); hideMenu();">Insert Row</li>
            <li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-600 cursor-pointer"
                @click="$wire.insertColumn(selected.col); hideMenu();">Insert Column</li>
            <li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-600 cursor-pointer"
                @click="$wire.deleteRow(selected.row); hideMenu();">Delete Row</li>
            <li class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-600 cursor-pointer"
                @click="$wire.deleteColumn(selected.col); hideMenu();">Delete Column</li>
        </ul>
    </div>
</div>

<script>
function tableInteraction() {
    return {
        selected: { row: null, col: null },
        menuVisible: false,
        menuStyle: { top: '0px', left: '0px' },

        editing: { row: null, col: null, value: '' },

        selectCell(row, col) {
            this.selected = { row, col };
            this.menuVisible = false;
        },

        isSelected(row, col) {
            return this.selected.row === row && this.selected.col === col;
        },

        editCell(row, col, cellEl) {
            this.selectCell(row, col);

            const currentContent = cellEl.innerText.trim();
            this.editing = { row, col, value: currentContent };

            this.$nextTick(() => {
                const input = cellEl.querySelector('input');
                if (input) input.focus();
            });
        },

        saveCell() {
            if (this.editing.row && this.editing.col) {
                const { row, col, value } = this.editing;

                this.$wire.updateCell(row, col, value);

                this.editing = { row: null, col: null, value: '' };
            }
        },

        openMenu(row, col, cellEl) {
            this.selectCell(row, col);

            const rect = cellEl.getBoundingClientRect();
            const mainContainer = cellEl.closest('.max-w-6xl');
            const containerRect = mainContainer.getBoundingClientRect();

            const offsetY = rect.top - containerRect.top - 10;
            const offsetX = rect.right - containerRect.left + 5;

            this.menuStyle = {
                top: offsetY + 'px',
                left: offsetX + 'px'
            };

            this.menuVisible = true;
        },

        hideMenu() {
            this.menuVisible = false;
        }
    };
}
</script>
