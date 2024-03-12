<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CafeExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cafe_id',
        'amount',
        'quantity',
        'details',
        'date',
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
