<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'details', 'status'];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                      ->orWhere('details', 'LIKE', '%' . $search . '%');
            });
        }
        return $query;
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'expense_type_id');
    }

}

