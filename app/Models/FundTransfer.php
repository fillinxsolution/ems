<?php

namespace App\Models;

use App\Traits\BankingTransactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundTransfer extends Model
{
    use HasFactory, BankingTransactions;


    protected $fillable = ['details', 'account_from', 'account_to', 'amount', 'date'];

    protected $hidden = [
        'created_at',
        'udpated_at',
    ];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('date', 'LIKE', '%' . $search . '%')
                ->orWhere('amount', 'LIKE', '%' . $search . '%')
                ->orWhereHas('accountFrom', function ($query) use ($search) {
                        $query->where('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('account_number', 'LIKE', '%' . $search . '%');
                });
            });
        }
        return $query;
    }

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
