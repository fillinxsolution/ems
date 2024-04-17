<?php

namespace App\Http\Controllers;


use App\Models\UserQualification;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserQualificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        try {
            $qualifications = UserQualification::where('user_id',$request->user_id)->get();
            return $this->sendResponse($qualifications, 200, ['Get List Successfully.'], true);
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
            ]);
            $userqualifications = UserQualification::where('user_id',$request->user_id)->get();
            if (count($userqualifications) > 0)
            {
                $userqualifications->delete();
            }
            foreach ($request->userQualification as  $qualifi) {
                $qualification = new UserQualification();
                $qualification->user_id = $request->user_id;
                $qualification->qualification_id = $qualifi['qualification_id'];
                $qualification->title = $qualifi['title'];
                $qualification->institute = $qualifi['institute'];
                $qualification->from = $qualifi['from'];
                $qualification->to = $qualifi['to'];
                $qualification->obtained_marks = $qualifi['obtained_marks'];
                $qualification->total_marks = $qualifi['total_marks'];
                $qualification->remarks = $qualifi['remarks'];
                $qualification->save();
            }

            return $this->sendResponse($qualification, 200, ['Stored Successfully.'], true);
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
    public function show(UserQualification $userQualification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserQualification $userQualification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserQualification $userQualification)
    {
      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserQualification $userQualification)
    {
        //
    }
}
