<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLoan extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id',
      'amount',
      'installments',
      'transferred_at',
      'status',
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
