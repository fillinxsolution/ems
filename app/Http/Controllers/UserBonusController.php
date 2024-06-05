<?php

namespace App\Http\Controllers;

use App\Models\ImportCsv;
use App\Models\ImportCsvDetail;
use App\Models\UserBonus;
use App\Models\UserDetail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserBonusController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-bonus-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-bonus-create|account-edit', ['only' => ['store']]);
        $this->middleware('permission:user-bonus-edit', ['only' => ['update']]);
        $this->middleware('permission:user-bonus-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = UserBonus::with('user')->search(($request->search) ? $request->search : '')
                ->paginate(($request->limit) ? $request->limit : 10);
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
            $importCsvDetail = ImportCsvDetail::where('salary_month_id', $request->salary_month_id)->where('user_id', $request->user_id)->first();
            if ($importCsvDetail) {
                $this->csvUpdate($request->salary_month_id, $request->user_id, $importCsvDetail);
            }
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
            $importCsvDetail = ImportCsvDetail::where('salary_month_id', $request->salary_month_id)->where('user_id', $request->user_id)->first();
            if ($importCsvDetail) {
                $this->csvUpdate($request->salary_month_id, $request->user_id, $importCsvDetail);
            }

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
            $salary_month_id = $userBonus->salary_month_id;
            $user_id = $userBonus->user_id;
            $userBonus->delete();
            $userBonuses = UserBonus::where('salary_month_id',$salary_month_id)->where('user_id',$user_id)->sum('amount');
            $importCsvDetail = ImportCsvDetail::where('salary_month_id', $salary_month_id)->where('user_id', $user_id)->first();
            if($importCsvDetail) {
                $importCsvDetail->bonus = $userBonuses;
                $importCsvDetail->save();
            }

            return $this->sendResponse(null, 200, ['Record deleted successfully.'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }
    public function csvUpdate($salary_month_id, $user_id,$importCsvDetail)
    {
        $userBonus = UserBonus::where('salary_month_id',$salary_month_id)->where('user_id',$user_id)->sum('amount');
        if($importCsvDetail){
            $importCsvDetail->bonus = $userBonus;
            $importCsvDetail->save();
        }
    }
}
