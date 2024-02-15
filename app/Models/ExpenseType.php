<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'details', 'status'];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'expense_type_id');
    }

}

