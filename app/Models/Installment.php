<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_loan_id',
        'amount',
        'date',
        'status',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(UserLoan::class, 'user_loan_id');
    }
}
