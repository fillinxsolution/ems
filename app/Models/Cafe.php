<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
    use HasFactory;

    protected $fillable = [
        'item', 'price', 'status'
    ];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('item', 'LIKE', '%' . $search . '%')
                      ->orWhere('price', 'LIKE', '%' . $search . '%')
                      ->orWhere('status', 'LIKE', '%' . $search . '%');
            });
        }
        return $query;
    }
}
