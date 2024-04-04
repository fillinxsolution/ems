<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    public function query()
    {
        return User::query()->with('details');
    }

    public function map($user): array
    {
        return [
            $user->empleado_id,
            $user->name,
            $user->email,
            $user->cnic,
            $user->mobile_no,
            $user->salary,
            $user->details->account_no ?? '',
        ];
    }
    public function headings(): array
    {
        return ['Empleado Id', 'Name', 'Email', 'CNIC','Mobile No.','Salary','Account No'];
    }
}
