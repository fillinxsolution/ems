<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function query()
    {

        return User::query()->select('empleado_id', 'name', 'email', 'cnic','mobile_no','salary');
    }

    public function headings(): array
    {
        return ['Empleado Id', 'Name', 'Email', 'CNIC','Mobile No.','Salary'];
    }
}
