<?php

namespace App\Http\Controllers;

use App\Models\LeaveManagement;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeaveManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $leaveManagements = LeaveManagement::with(['user','leaveType'])
                ->paginate(($request->limit) ? $request->limit : 10);
            return $this->sendResponse($leaveManagements, 200, ['Get List Successfully.'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'user_id' => 'required',
                'leave_type_id' => 'required',
                'allow_leaves' => 'required',
            ]);
            $leaveType = LeaveManagement::create($request->all());

            return $this->sendResponse($leaveType, 200, ['Stored Successfully.'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveManagement $leaveManagement)
    {
        try {
            return $this->sendResponse($leaveManagement, 200, ['Data get successfully,'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveManagement $leaveManagement)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'leave_type_id' => 'required',
            'allow_leaves' => 'required',
        ]);
        try {
            $leaveManagement->update($request->only(['user_id','allow_leaves','leave_type_id']));
            return $this->sendResponse($leaveManagement, 200, ['Updated successfully.'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveManagement $leaveManagement)
    {
        try {
            $leaveManagement->delete();
            return $this->sendResponse(null, 200, ['Record deleted successfully.'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }
}
