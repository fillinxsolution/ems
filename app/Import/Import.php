<?php
namespace App\Import;

use App\Models\ImportCsvDetail;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class Import implements ToCollection {
    public $id = '';
    public function __construct($id) {
        $this->id = $id;
    }
    public function collection(Collection $rows) {
        $data = [];
        foreach ($rows as $index => $row) {


            if($row[0] == 'For the month 1/2024'){
                $record['employee'] = $rows[$index + 1]->filter()->toArray();
            }
            if($row[0] == 'Total Days'){
                $record['days']     = $row->filter()->toArray();
                $record['expected'] = $rows[$index + 1]->filter()->toArray();
                array_push($data, $record);
            }
        }

        foreach($data as $new_row ) {


            $emp_id = explode(" ", $new_row['employee'][0]);
            $emp_id = end($emp_id);
            $user = User::where('empleado_id', $emp_id)->first();
            // if($user){
                $total_days     = isset($new_row['days'][2])    ? explode(" ", $new_row['days'][2] ?? "0")[0] : 0;
                $present_days   = isset($new_row['days'][6])    ? explode(" ", $new_row['days'][6] ?? "0")[0] : 0;
                $late_min       = isset($new_row['days'][10])   ? explode(" ", $new_row['days'][10] ?? "0")[0] : 0;

                $annual_leaves =  isset($new_row['days'][13]) ? explode(",",$new_row['days'][13]) : 0;
                if($annual_leaves != 0){
                    $annual_leaves_total = explode("=", $annual_leaves[0]);
                    $annual_leaves_total = $annual_leaves_total[1];
                    $annual_leaves_availed = explode("=", str($annual_leaves[1])->squish());
                    $annual_leaves_availed = $annual_leaves_availed[1];
                }else{
                    $annual_leaves_total    = 0;
                    $annual_leaves_availed  = 0;
                }

                $expected_hrs = 0;
                $expected_min = 0;
                if(isset($new_row['expected'][2])){
                    if(Str::contains($new_row['expected'][2], ',') ){
                        $total_expected = explode(",", $new_row['expected'][2]);
                        $expected_hrs = explode(" ", $total_expected[0] ?? "0")[0];
                        $expected_min = explode(" ", str($total_expected[1])->squish() ?? "0")[0];
                    } else {
                        $expected_hrs = explode(" ", $new_row['expected'][2] ?? "0")[0];
                        $expected_min = 0;
                    }
                }

                $earned_hrs = 0;
                $earned_min = 0;
                if(isset($new_row['expected'][6])){
                    if(Str::contains($new_row['expected'][6], ',') ){
                        $total_earned = explode(",", $new_row['expected'][6]);
                        $earned_hrs = explode(" ", $total_earned[0] ?? "0")[0];
                        $earned_min = explode(" ", str($total_earned[1])->squish() ?? "0")[0];
                    } else {
                        $earned_hrs = explode(" ", $new_row['expected'][6] ?? "0")[0];
                        $earned_min = 0;
                    }
                }


                $overtime_hrs = 0;
                $overtime_min = 0;
                if(isset($new_row['expected'][10])){
                    if(Str::contains($new_row['expected'][10], ',') ){
                        $total_overtime = explode(",", $new_row['expected'][10]);
                        $overtime_hrs = explode(" ", $total_overtime[0] ?? "0")[0];
                        $overtime_min = explode(" ", str($total_overtime[1])->squish() ?? "0")[0];
                    } else {
                        $overtime_hrs = explode(" ", $new_row['expected'][10] ?? "0")[0];
                        $overtime_min = 0;
                    }
                }

                ImportCsvDetail::create([
                    "user_id" => ($user) ? $user->id : null,
                    "import_csvs_id" => $this->id,
                    "total_days" => $total_days,
                    "present_days" => $present_days,
                    "late_min" => $late_min,
                    "annual_leaves_total" => $annual_leaves_total,
                    "annual_leaves_availed" => $annual_leaves_availed,
                    "expected_hrs" => $expected_hrs,
                    "expected_min" => $expected_min,
                    "earned_hrs" => $earned_hrs,
                    "earned_min" => $earned_min,
                    "overtime_hrs" => $overtime_hrs,
                    "overtime_min" => $overtime_min,

                ]);
            // }
        }
        // return $data;
    }
}
