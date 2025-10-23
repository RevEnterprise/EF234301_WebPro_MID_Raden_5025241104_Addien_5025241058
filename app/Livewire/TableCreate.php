<?php

namespace App\Livewire;

use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Str;

class TableCreate extends Component
{
    public $name;
    public $url;
    public $row_count = 4;
    public $column_count = 4;

    protected $rules = [
        'name' => 'required|string|max:255',
        'url' => 'nullable|url|max:255',
        'row_count' => 'nullable|integer|min:0',
        'column_count' => 'nullable|integer|min:0',
    ];

    public function save()
    {
        $this->validate();

        do {
            $slug = Str::slug($this->name);
            $random = Str::lower(Str::random(6));
            $url = "{$slug}-{$random}";
        } while (Table::where('url', $url)->exists());

        $table = Table::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'url' => $url,
            'row_count' => $this->row_count,
            'column_count' => $this->column_count,
        ]);

        // Redirect to the table’s view page
        return redirect()->route('tables.show', $table->url);
    }

    public function render()
    {
        return view('livewire.table-create');
    }
}
