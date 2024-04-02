<?php

namespace App\Exports;

use App\Models\ImportCsvDetail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;


class CsvExport implements FromQuery, WithHeadings
{
    protected $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    public function query()
    {

        return ImportCsvDetail::query()->where('salary_month_id', $this->id)->select(
            'empleado_id',
            'name',
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
            'month_salary',
        );
    }

    public function headings(): array
    {
        return [
            'Empleado Id',
            'Name',
            "Total Days",
            "Present Days",
            "Late Min",
            "Annual leaves total",
            "Annual leaves availed",
            "Expected hrs",
            "Expected min",
            "Earned hrs",
            "Earned min",
            "Overtime hrs",
            "Overtime min",
            "Earned time in min",
            "Salary in min",
            'Loan deduction',
            'Fine deduction',
            'Cafe deduction',
            'Wfh',
            'Bonus',
            'Month salary',
        ];
    }
}
