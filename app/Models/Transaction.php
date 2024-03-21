<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable  = ['transaction_id', 'date', 'type', 'category', 'account_id', 'amount'];

    protected $hidden = [
        'created_at',
        'udpated_at',
    ];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('date', 'LIKE', '%' . $search . '%')
                ->orWhere('category', 'LIKE', '%' . $search . '%')
                ->orWhereHas('account', function ($query) use ($search) {
                        $query->where('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('account_number', 'LIKE', '%' . $search . '%');
                });
            });
        }
        return $query;
    }
    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $model->transaction_id = 'TRA-' . str_pad($model->id, 7, "0", STR_PAD_LEFT);
            $model->save();
        });
    }

    /**
     * Interact with the date.
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = strtotime($value);
    }


    public function account()
    {
        return $this->hasOne(Account::class, 'id', 'account_id');
    }
}
