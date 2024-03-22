<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryMonth extends Model
{
    use HasFactory;

    protected $table = 'salary_months';

    protected $fillable = ['name','month','year','status'];


}
