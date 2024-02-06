<?php

namespace App\Models;

use App\Traits\BankingTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundTransfer extends Model
{
    use HasFactory, BankingTransactions;


    protected $fillable = ['details', 'account_from', 'account_to', 'amount', 'date'];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = strtotime($value);
    }

    public function accountFrom() {
        return $this->hasOne(Account::class, 'id', 'account_from');
    }

    public function accountTo() {
        return $this->hasOne(Account::class, 'id', 'account_to');
    }
}
