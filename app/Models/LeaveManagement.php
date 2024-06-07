<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveManagement extends Model
{
    use HasFactory;


    protected $table = 'leave_managements';

    protected $fillable = ['user_id','leave_type_id','allow_leaves'];

    protected $hidden  = ['created_at','updated_at'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

}
