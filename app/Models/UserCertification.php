<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCertification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'institute',
        'image',
        'certificated_at',
        'details',
    ];
}
