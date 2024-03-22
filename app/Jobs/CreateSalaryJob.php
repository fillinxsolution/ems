<?php

namespace App\Jobs;

use App\Models\ImportCsv;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSalaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $csv_id;

    /**
     * Create a new job instance.
     */
    public function __construct($csv_id)
    {
        $this->csv_id = $csv_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $csv = ImportCsv::with('imports.user')->findOrFail($this->csv_id);
        foreach ($csv->imports as $key => $import) {

            if ($import->user_id != null) {
                $expected_hours = $import->expected_hrs;
                $basic_salary = $import->user->salary;

                $per_hour_salary = round($basic_salary / $expected_hours, 2);
                $per_min_salary = $per_hour_salary / 60;

                $total_earned_hours = $import->earned_hrs + $import->overtime_hrs;
                $earned_min = $import->earned_min + $import->overtime_min;

                $earned_hours_in_min = round($total_earned_hours * 60, 2);


                $total_earned_minuts = $earned_hours_in_min + $earned_min;

                $import->update([
                    'earned_time_in_min' => $total_earned_minuts,
                    'salary_in_min' => $per_min_salary,
                    'month_salary' => round($per_min_salary * $total_earned_minuts, 2)
                ]);

            }
        }
    }
}
