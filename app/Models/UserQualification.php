<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQualification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'qualification_id',
        'title',
        'institute',
        'from',
        'to',
        'obtained_marks',
        'total_marks',
        'remarks',
    ];

    public function qualification(){
        return $this->belongsTo(UserQualification::class, 'qualification_id');
    }

}
