<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::with('permissions')->get();
            return $this->sendResponse($roles, 200, ['Roles List'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    public function show(Role $role)
    {
        try {
            $role->load('permissions');
            $permissions = Permission::where('guard_name', 'api')->get();
            $data['role'] = $role;
            $data['permissions'] = $permissions;
            return $this->sendResponse($data, 200, ['Role By ID'], true);
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
            $data = [
                ...$request->only(['name']),
                'guard_name'=> 'api'
            ];
            $role = Role::create($data);
            $permissions = $request->permissions;
            if($permissions){
                $role->syncPermissions($permissions);
            }
            $role->load('permissions');
            return $this->sendResponse($role, 200, ['Role Created Successfully'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }

    }

    public function update(Request $request,Role $role)
    {
        try {
            $data = [
                ...$request->only(['name']),
                'guard_name'=> 'api'
            ];
            $role->update($data);
            $permissions = $request->permissions;
            if($permissions){
                $role->syncPermissions($permissions);
            }
            $role->load('permissions');
            return $this->sendResponse($role, 200, ['Role Updated Successfully'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }

    }


    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return $this->sendResponse($role, 200, ['Role Deleted Successfully'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }

    }
}
