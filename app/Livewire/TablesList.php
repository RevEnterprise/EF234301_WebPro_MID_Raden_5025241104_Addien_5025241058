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
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';


    public function mount()
    {
        $this->loadTables();
    }

    public function updatedSearch()
    {
        $this->loadTables();
    }

    public function loadTables()
    {
        $query = Table::where('user_id', Auth::id());

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $sortField = $this->sortField ?? 'name';
        $sortDirection = $this->sortDirection ?? 'asc';

        $query->orderBy($sortField, $sortDirection);

        $this->tables = $query->get();
    }


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->loadTables();
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

        $this->tables = Table::where('user_id', Auth::id())
            ->orderBy('name')
            ->get();
    }

    public function openFirstResult()
    {
        $this->loadTables();

        if ($this->tables->isEmpty()) {
            return;
        }

        $firstTable = $this->tables->first();

        return redirect()->route('tables.show', $firstTable);
    }

    public function render()
    {
        if (!Auth::check()) {
            return view('livewire.tables-list', ['tables' => collect()]);
        }

        if ($this->tables === null) {
            $this->loadTables();
        }

        $filtered = Table::where('user_id', Auth::id())
        ->orderBy($this->sortField, $this->sortDirection)
        ->get();

        return view('livewire.tables-list', [
            'tables' => $filtered ?? collect(),
        ]);

    }
}
