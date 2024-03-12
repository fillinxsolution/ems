<?php

namespace App\Http\Controllers;

use App\Models\WorkFromHome;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WorkFromHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $wfh = WorkFromHome::with('user')->get();
            return $this->sendResponse($wfh, 200, ['Get List Successfully.'], true);
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
                'check_in' => 'required',
                'check_out' => 'required',
                'date' => 'required',
            ]);
            $data = [...$request->all()];
            $startTime = Carbon::createFromFormat('H:i', $request->check_in);
            $endTime = Carbon::createFromFormat('H:i', $request->check_out);
            $data['minutes'] = $endTime->diffInMinutes($startTime);
            $data['salary'] = 9;
            $wfh = WorkFromHome::create($data);

            return $this->sendResponse($wfh, 200, ['Stored Successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkFromHome $wfh)
    {
        try {
            return $this->sendResponse($wfh, 200, ['Data get successfully,'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkFromHome $wfh)
    {
        try {
            $this->validate($request, [
                'user_id' => 'required',
                'check_in' => 'required',
                'check_out' => 'required',
                'date' => 'required',
            ]);
            $data = [...$request->all()];
            $startTime = Carbon::createFromFormat('H:i', '10:00');
            $endTime = Carbon::createFromFormat('h:i A', '10:00 PM');
            $data['minutes'] = $endTime->diffInMinutes($startTime);
            $data['salary'] = 9;
            $wfh->update($data);
            return $this->sendResponse($wfh, 200, ['Updated successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkFromHome $wfh)
    {
        try {
            $wfh->delete();
            return $this->sendResponse(null, 200, ['Record deleted successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }
}
