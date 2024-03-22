<?php

namespace App\Http\Controllers;

use App\Models\UserBonus;
use App\Models\UserDetail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserBonusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = UserBonus::with('user')->get();
            return $this->sendResponse($data, 200, ['Get List Successfully.'], true);
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
                'amount' => 'required',
                'date' => 'required',
                'details' => 'required',
                'user_id' => 'required',
                'salary_month_id' => 'required',
            ]);

            $userBonus = UserBonus::create($request->only([
                'amount',
                'date',
                'details',
                'user_id',
                'salary_month_id',
            ]));

            return $this->sendResponse($userBonus, 200, ['Stored Successfully.'], true);
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
    public function show(UserBonus $userBonus)
    {
        try {
            return $this->sendResponse($userBonus, 200, ['Data get successfully,'], true);
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
    public function update(Request $request, UserBonus $userBonus)
    {
        try {
            $this->validate($request, [
                'amount' => 'required',
                'date' => 'required',
                'details' => 'required',
                'user_id' => 'required',
                'salary_month_id' => 'required',
            ]);
            $userBonus->update($request->only([
                'amount',
                'date',
                'details',
                'user_id',
                'salary_month_id',
            ]));

            return $this->sendResponse($userBonus, 200, ['Updated successfully.'], true);
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
    public function destroy(UserBonus $userBonus)
    {
        try {
            $userBonus->delete();
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
