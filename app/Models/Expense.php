<?php

namespace App\Models;

use App\Traits\BankingTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory, BankingTransactions;


    protected $fillable = ['user_id', 'date', 'type', 'salary_month_id', 'expense_type_id', 'account_id', 'status', 'details',  'amount'];

    protected $hidden = [
        'created_at',
        'udpated_at',
    ];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('date', 'LIKE', '%' . $search . '%')
                ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('account', function ($query) use ($search) {
                        $query->where('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('account_number', 'LIKE', '%' . $search . '%');
                });
            });
        }
        return $query;
    }

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
