<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'url',
        'row_count',
        'column_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cells()
    {
        return $this->hasMany(Cell::class);
    }

    public function getRouteKeyName()
    {
        return 'url';
    }
}
