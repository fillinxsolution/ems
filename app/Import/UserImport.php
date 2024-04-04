<?php

namespace App\Import;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection ,WithHeadingRow
{


    public function __construct() {

    }
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows) {

        foreach($rows as $key=>$new_row ) {
            $existingUser = User::where('email', '=', $new_row['email'])->first();

            if (!$existingUser) {
             $user = User::create([
                    "empleado_id" => $new_row['empleado_id'] ?? null,
                    "name" => $new_row['name'] ?? null,
                    "email" => $new_row['email'] ?? null,
                    "cnic" => $new_row['cnic'] ?? null,
                    'password' => Hash::make('123456789'),
                    "mobile_no" => $new_row['mobile_no'] ?? null,
                    "salary" => $new_row['salary'] ?? null,
                ]);
                UserDetail::create([
                     "user_id" => $user->id ?? null,
                     "account_no" => $new_row['account_no'] ?? null,
                ]);
            }
        }

    }
}
