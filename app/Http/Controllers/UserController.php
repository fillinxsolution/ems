<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Import\Import;
use App\Models\ImportCsv;
use App\Models\ImportCsvDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:users-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:users-create|users-edit', ['only' => ['store',]]);
        $this->middleware('permission:users-edit', ['only' => ['update']]);
        $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {
            $users = User::with('roles')
                ->whereDoesntHave('roles', function($query) {
                    $query->where('name', 'Super Admin'); // Assuming the role name is stored in the 'name' column
                })
                ->search($request->search ?? '')
                ->paginate($request->limit ?? 10);
            return $this->sendResponse($users, 200, ['Users List'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    public function list()
    {
        try {
            $users = User::with('roles')
                ->whereDoesntHave('roles', function($query) {
                    $query->where('name', 'Super Admin'); // Assuming the role name is stored in the 'name' column
                })->get();
            return $this->sendResponse($users, 200, ['Users List'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    public function show(User $user)
    {
        try {
            $user->load('accounts');
            return $this->sendResponse($user, 200, ['User Details'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    public function accounts()
    {
        try {
            $user = auth()->user();
            $user->load('accounts.bank');
            return $this->sendResponse($user, 200, ['User Details'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    public function expenses(Request $request)
    {
        try {
            $user = auth()->user();
            $user->load('expenses.account', 'expenses.expenseType');
            return $this->sendResponse($user, 200, ['User Details'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    public function transections(Request $request)
    {
        try {
            $user = auth()->user();
            $user->load('accounts.transactions');
            return $this->sendResponse($user, 200, ['Transection Details'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'name'      => 'required',
                'email'     => 'required|unique:users,email',
//                'cnic'     => 'required|unique:users,cnic',
                'password'  => 'required|confirmed',
            ]);
            $user = User::create($request->all());
            if ($request->is_admin == 0) {
                $request->validate([
                    'details.gender' => 'required',
                    'details.joining_date' => 'required',
                ]);
                $user->details()->create($request->details);
            }
            $user->assignRole((int) $request->role);
            $user->load('roles.permissions');
            DB::commit();
            return $this->sendResponse($user, 200, ['User Created Successfully'], true);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => "required|unique:users,name,{$user->id}",
            'email' => "required|unique:users,email,{$user->id}",
            'empleado_id' => "required|unique:users,empleado_id,{$user->id}",
            'salary' => "required"
        ]);
        try {
            $user->update(['name' => $request->name, 'email' => $request->email, 'empleado_id' => $request->empleado_id, 'salary' => $request->salary,
            'cnic' => $request->cnic, 'mobile_no' => $request->mobile_no]);
            if(isset($request->role)){
                $user->assignRole($request->role);
            }
            // $user->details()->update($request->details);
            return $this->sendResponse($user, 200, ['User Updated Successfully'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }


    public function destroy(User $user)
    {
        try {
            $user->delete();
            return $this->sendResponse(null, 200, ['User Deleted Successfully'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    public function import(Request $request)   {
        try{
            $request->validate([
                'file' => 'required|mimes:xlsx,xls',
                'month' => 'required|numeric|between:1,12',
                'year' => 'required|numeric|digits:4',
                'salary_month_id' => 'required',
            ]);

            $file = $request->file('file');
            $name = $request->month .'-'. $request->year;

            $check_old_csv = ImportCsv::where('name', $name)->first();
            if($check_old_csv){
                return $this->sendResponse(null, 404, ['Sheet Already Exist for this month please remove it first.'], false);
            }
            $path = $file->storeAs('public', $name);
            $import_csv = ImportCsv::create([
                'name' => $name,
                'path' => $path,
                'month' => $request->month,
                'year' => $request->year,
                'salary_month_id' => $request->salary_month_id,
            ]);
            // Process the Excel file
            Excel::import(new Import($import_csv), $file);

            return $this->sendResponse(null, 200, ['Excel file imported successfully!'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    public function salaryDetail(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
            ]);
            $importDetail = ImportCsvDetail::with(['user','salaryMonth'])->where('user_id',$request->user_id)->orderBy('salary_month_id', 'desc')->get();
            return $this->sendResponse($importDetail, 200, ['Users List'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    public function userExport()
    {
        $abc = Excel::store(new UsersExport(), 'User.csv');
        dd($abc);
        // return Excel::download(new UsersExport(), 'users.xlsx');
    }
}
