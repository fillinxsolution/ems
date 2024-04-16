<?php

namespace App\Http\Controllers;

use App\Models\UserCertification;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserCertificationController extends Controller
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
            $userCertifications = UserCertification::where('user_id',$request->user_id)->get();
            return $this->sendResponse($userCertifications, 200, ['Get List Successfully.'], true);
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
            foreach ($request->userCertification as  $certification) {
                $certif = new UserCertification();
                $certif->user_id = $request->user_id;
                $certif->title = $certification['title'];
                $certif->institute = $certification['institute'];
                $certif->certificated_at = $certification['certificated_at'];
                $certif->details = $certification['details'];
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
    public function show(UserCertification $userCertification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserCertification $userCertification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserCertification $userCertification)
    {
        $userCertifications = UserCertification::where('user_id',$request->user_id)->delete();
        try {
            $this->validate($request, [
                'user_id' => 'required',
            ]);
            foreach ($request->userCertification as  $certification) {
                $certif = new UserCertification();
                $certif->user_id = $request->user_id;
                $certif->title = $certification['title'];
                $certif->institute = $certification['institute'];
                $certif->certificated_at = $certification['certificated_at'];
                $certif->details = $certification['details'];
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
     * Remove the specified resource from storage.
     */
    public function destroy(UserCertification $userCertification)
    {
        //
    }
}
