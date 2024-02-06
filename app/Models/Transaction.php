<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable  = ['transaction_id', 'date', 'type', 'category', 'account_id', 'amount'];
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
