<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cell extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'row_coordinate',
        'column_coordinate',
        'content',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
