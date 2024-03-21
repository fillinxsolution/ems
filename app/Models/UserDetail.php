<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'father_name',
        'gender',
        'martial_status',
        'birth_date',
        'blood_group',
        'phone_no',
        'joining_date',
        'current_address',
        'permanent_address',
    ];
}
