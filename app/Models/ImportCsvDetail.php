<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportCsvDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "import_csvs_id",
        "total_days",
        "present_days",
        "late_min",
        "annual_leaves_total",
        "annual_leaves_availed",
        "expected_hrs",
        "expected_min",
        "earned_hrs",
        "earned_min",
        "overtime_hrs",
        "overtime_min",
        "earned_time_in_min",
        "salary_in_min",
        'loan_deduction',
        'fine_deduction',
        'cafe_deduction',
        'wfh',
        'bonus',
        'month_salary'
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function import_csv(){
        return $this->belongsTo(ImportCsv::class, 'import_csvs_id');
    }
}
