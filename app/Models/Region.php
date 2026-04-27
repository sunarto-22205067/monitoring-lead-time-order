<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'parent_name',
    ];

    public static $types = ['Witel', 'Datel', 'STO'];

    public function scopeWitel($query)
    {
        return $query->where('type', 'Witel');
    }

    public function scopeDatel($query)
    {
        return $query->where('type', 'Datel');
    }

    public function scopeSto($query)
    {
        return $query->where('type', 'STO');
    }
}
