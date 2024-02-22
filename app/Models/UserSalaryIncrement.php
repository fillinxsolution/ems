<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSalaryIncrement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'previous_salary',
        'incremented_at',
        'increment_amount',
    ];
}
