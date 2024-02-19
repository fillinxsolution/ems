<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:permission-list', ['only' => ['index']]);
        $this->middleware('permission:permission-create|permission-edit', ['only' => ['store']]);
        $this->middleware('permission:permission-edit', ['only' => ['update']]);
    }

    public function index()
    {
        try {
            $permissions = Permission::where('guard_name', 'api')->get();
            return $this->sendResponse($permissions, 200, ['Permissions List'], true);
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
            'name' => 'required|unique:permissions,name|regex:/^\S*$/u',
            'display_name' => 'required'
        ]);
        try {
            $permission = Permission::create([
                'name' => $request->input('name'),
                'display_name' => $request->input('display_name'),
                'guard_name' => 'api'
            ]);
            $role = Role::where('name', 'Super Admin')->first();
            if ($role) {
                $role->givePermissionTo($permission);
            }
            return $this->sendResponse($permission, 200, ['Permission Created Successfully'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    public function update(Request $request, Permission $permission)
    {
        try {
            $permission->display_name = $request->display_name;
            $permission->guard_name = 'api';
            $permission->save();
            return $this->sendResponse($permission, 200, ['Permission Updated Successfully'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }
}
