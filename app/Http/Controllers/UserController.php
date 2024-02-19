<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function __construct() {
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
        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required|unique:users,email',
            'password'  => 'required|confirmed',
        ]);
        try {
            $user = User::create($request->all());
            $user->assignRole($request->role);
            $user->load('roles.permissions');
            return $this->sendResponse($user, 200, ['User Created Successfully'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }

    }

    public function update(Request $request,User $user)
    {
        $this->validate($request, [
            'name'      => 'required'
        ]);
        try {
            $user->update(['name'=> $request->name]);
            $user->assignRole($request->role);
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
