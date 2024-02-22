<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDesignation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'designation_id',
        'report_to',
    ];
}
