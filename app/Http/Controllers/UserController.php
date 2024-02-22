<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:users-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:users-create|users-edit', ['only' => ['store',]]);
        $this->middleware('permission:users-edit', ['only' => ['update']]);
        $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $users = User::with('roles')->get();
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
                'cnic'     => 'required|unique:users,cnic',
                'password'  => 'required|confirmed',
            ]);
            $user = User::create($request->all());
            if ($request->is_admin == 0) {
                $request->validate([
                    'details.gender' => 'required',
                    'details.salary' => 'required',
                    'details.joining_date' => 'required',
                ]);
                $user->details()->create($request->details);
            }
            $user->assignRole($request->role);
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
            'name'      => 'required'
        ]);
        try {
            $user->update(['name' => $request->name]);
            $user->assignRole($request->role);
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
}
