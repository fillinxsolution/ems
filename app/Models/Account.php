<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    protected $fillable = ['title', 'account_number', 'bank_id','user_id', 'status', 'balance'];

    protected $hidden = [
        'created_at',
        'udpated_at',
    ];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%')
                      ->orWhere('account_number', 'LIKE', '%' . $search . '%');
            });
        }
        return $query;
    }

    public function bank(){
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'account_id', 'id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'account_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function fundTransfersFrom(){
        return $this->hasMany(FundTransfer::class, 'account_from', 'id');
    }
    public function fundTransfersTo(){
        return $this->hasMany(FundTransfer::class, 'account_to', 'id');
    }


}
