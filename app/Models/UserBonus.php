<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBonus extends Model
{
    use HasFactory;

    protected $table = 'user_bonuses';

    protected $fillable = [
        'user_id',
        'amount',
        'date',
        'details',
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
