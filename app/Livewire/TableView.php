<?php

namespace App\Livewire;

use App\Models\Table;
use App\Models\Cell;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TableView extends Component
{
    public Table $table;

    public $isRenaming = false;
    public $newName = '';

    public $cells = [];

    public function mount(Table $table)
    {
        abort_unless($table->user_id === Auth::id(), 403);

        $this->table = $table;

        $this->loadCells();
    }

    protected function loadCells()
    {
        $cells = $this->table->cells()->get();

        $this->cells = [];

        foreach ($cells as $cell) {
            $this->cells[$cell->row_coordinate][$cell->column_coordinate] = $cell->content;
        }
    }

    public function updatedCell($value, $key)
    {

    }

    public function updateCell($row, $col, $content)
    {
        $cell = Cell::firstOrNew([
            'table_id' => $this->table->id,
            'row_coordinate' => $row,
            'column_coordinate' => $col,
        ]);

        $cell->content = $content;
        $cell->save();

        $this->cells[$row][$col] = $content;
    }

    public function startRename()
    {
        $this->isRenaming = true;
        $this->newName = $this->table->name;
    }

    public function cancelRename()
    {
        $this->isRenaming = false;
        $this->newName = '';
    }

    public function saveRename()
    {
        $this->validate([
            'newName' => 'required|string|max:255',
        ]);

        $this->table->update([
            'name' => $this->newName,
        ]);

        $this->table->refresh();
        $this->isRenaming = false;
        $this->newName = '';
    }

    public function insertRow($row)
    {
        $this->table->increment('row_count');
        $this->table->refresh();
        $this->loadCells();
    }

    public function deleteRow($row)
    {
        if ($this->table->row_count > 1) {
            $this->table->decrement('row_count');
        }
        $this->table->refresh();
        $this->loadCells();
    }

    public function insertColumn($col)
    {
        $this->table->increment('column_count');
        $this->table->refresh();
        $this->loadCells();
    }

    public function deleteColumn($col)
    {
        if ($this->table->column_count > 1) {
            $this->table->decrement('column_count');
        }
        $this->table->refresh();
        $this->loadCells();
    }

    public function render()
    {
        return view('livewire.table-view');
    }
}

