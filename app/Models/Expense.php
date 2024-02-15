<?php

namespace App\Models;

use App\Traits\BankingTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory, BankingTransactions;


    protected $fillable = ['user_id', 'date', 'type', 'expense_type_id', 'account_id', 'status', 'details',  'amount'];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function account() {
        return $this->hasOne(Account::class, 'id', 'account_id');
    }

    public function expenseType() {
        return $this->hasOne(ExpenseType::class, 'id', 'expense_type_id');
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = strtotime($value);
    }
}
