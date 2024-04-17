<?php

namespace App\Http\Controllers;

use App\Models\UserExperience;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserExperienceController extends Controller
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
            $userExperience = UserExperience::where('user_id',$request->user_id)->get();
            return $this->sendResponse($userExperience, 200, ['Get List Successfully.'], true);
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

            $userExperiences = UserExperience::where('user_id',$request->user_id)->get();
            if (count($userExperiences) > 0)
            {
                $userExperiences->delete();
            }
            foreach ($request->userExperience as  $experience) {
                $certif = new UserExperience();
                $certif->user_id = $request->user_id;
                $certif->title = $experience['title'];
                $certif->institute = $experience['institute'];
                $certif->designation = $experience['designation'];
                $certif->from = $experience['from'];
                $certif->to = $experience['to'];
                $certif->leaving_reason = $experience['leaving_reason'];
                $certif->remarks = $experience['remarks'];
                $certif->save();
            }
            return $this->sendResponse($certif, 200, ['Stored Successfully.'], true);
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
    public function show(UserExperience $userExperience)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserExperience $userExperience)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserExperience $userExperience)
    {
//        $userExperiences = UserExperience::where('user_id',$request->user_id)->delete();
//        try {
//            $this->validate($request, [
//                'user_id' => 'required',
//            ]);
//            foreach ($request->userExperience as  $experience) {
//                $certif = new UserExperience();
//                $certif->user_id = $request->user_id;
//                $certif->title = $experience['title'];
//                $certif->institute = $experience['institute'];
//                $certif->designation = $experience['designation'];
//                $certif->from = $experience['from'];
//                $certif->to = $experience['to'];
//                $certif->leaving_reason = $experience['leaving_reason'];
//                $certif->remarks = $experience['remarks'];
//                $certif->save();
//            }
//            return $this->sendResponse($certif, 200, ['Stored Successfully.'], true);
//        } catch (QueryException $e) {
//            Log::error('Database error: ' . $e->getMessage());
//            return $this->sendResponse(null, 500, [$e->getMessage()], false);
//        } catch (\Exception $e) {
//            Log::error('Error: ' . $e->getMessage());
//            return $this->sendResponse(null, 500, [$e->getMessage()], false);
//        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserExperience $userExperience)
    {
        //
    }
}
