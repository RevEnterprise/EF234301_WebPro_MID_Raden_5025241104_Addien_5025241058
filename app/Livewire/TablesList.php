<?php

namespace App\Livewire;

use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TablesList extends Component
{
    public $tables;
    public $renameTableId = null;
    public $newName = '';

    public function mount()
    {
        $this->loadTables();
    }

    public function loadTables()
    {
        $this->tables = Table::where('user_id', Auth::id())
            ->orderBy('name', 'asc')
            ->get();
    }

    public function confirmRename($id)
    {
        $this->renameTableId = $id;
        $this->newName = Table::find($id)?->name ?? '';
    }

    public function saveRename()
    {
        $this->validate([
            'newName' => 'required|string|max:255',
        ]);

        $table = Table::where('id', $this->renameTableId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $table->update(['name' => $this->newName]);
        $this->renameTableId = null;
        $this->newName = '';
        $this->loadTables();
    }

    public function cancelRename()
    {
        $this->renameTableId = null;
        $this->newName = '';
    }

    public function deleteTable($id)
    {
        $table = Table::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $table->delete();
        $this->loadTables();
    }

    public function delete($tableId)
    {
        $table = Table::where('id', $tableId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $table->delete();

        // Refresh the list
        $this->tables = Table::where('user_id', Auth::id())
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.tables-list');
    }
}
