<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveBalance;
use App\Models\LeaveManagement;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $leaveTypes = LeaveApplication::with(['user','leaveType'])->paginate(($request->limit) ? $request->limit : 10);
            return $this->sendResponse($leaveTypes, 200, ['Get List Successfully.'], true);
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
                'from' => 'required',
                'to' => 'required',
                'reason' => 'required',
                'status' => 'required',
            ]);
            $data = $request->all();
            $from = Carbon::createFromFormat('d-m-Y', $request->from);
            $to = Carbon::createFromFormat('d-m-Y', $request->to);
            $daysCount = $to->diffInDays($from);
            $data['numbers_of_days'] = $daysCount;
            $data['status'] = $request->status;
            $leaveType = LeaveApplication::create($data);

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
    public function show(LeaveApplication $leaveApplication)
    {
        try {
            return $this->sendResponse($leaveApplication, 200, ['Data get successfully,'], true);
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
    public function update(Request $request, LeaveApplication $leaveApplication)
    {
        try {
            $this->validate($request, [
                'user_id' => 'required',
                'leave_type_id' => 'required',
                'from' => 'required',
                'to' => 'required',
                'reason' => 'required',
                'status' => 'required'
            ]);
            $data = $request->all();
            $from = Carbon::createFromFormat('d-m-Y', $request->from);
            $to = Carbon::createFromFormat('d-m-Y', $request->to);
            $daysCount = $to->diffInDays($from);
            $data['numbers_of_days'] = $daysCount;
            $leaveApplication->update($data);
            if ($leaveApplication) {
                $leaveApplications = LeaveBalance::where('user_id', $request->user_id)->where('status', 'Approved')->where('leave_type_id', $request->leave_type_id)->sum('balance');
                $leaveBalance = LeaveBalance::where('user_id', $request->user_id)->where('leave_type_id', $request->leave_type_id)->first();
                $allowedLeaves = LeaveManagement::where('user_id', $request->user_id)->where('leave_type_id', $request->leave_type_id)->sum('allow_leaves');
                $rem = $allowedLeaves - $leaveApplications;
                if ($leaveBalance) {
                    $leaveBalance->remaining_leaves = $rem;
                    $leaveBalance->save();
                } else {
                    LeaveBalance::create([
                        'user_id' => $request->user_id,
                        'leave_type_id' => $request->leave_type_id,
                        'remaining_leaves' => $rem,
                    ]);
                }
            }

            return $this->sendResponse($leaveApplication, 200, ['Stored Successfully.'], true);
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
    public function destroy(LeaveApplication $leaveApplication)
    {
        try {
            $leaveApplication->delete();
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
