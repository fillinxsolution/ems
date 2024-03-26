<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\ImportCsv;
use App\Models\ImportCsvDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FineController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:fine-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:fine-create|account-edit', ['only' => ['store']]);
        $this->middleware('permission:fine-edit', ['only' => ['update']]);
        $this->middleware('permission:fine-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $fines = Fine::with('user')->search(($request->search) ? $request->search : '')
            ->paginate(($request->limit) ? $request->limit : 10);
            return $this->sendResponse($fines, 200, ['Get List Successfully.'], true);
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
                'amount' => 'required',
                'date' => 'required',
                'salary_month_id' => 'required',
            ]);
            $fine = Fine::create($request->only([
                'user_id',
                'amount',
                'details',
                'date',
                'salary_month_id'
            ]));
            $importCsvDetail = ImportCsvDetail::where('salary_month_id',$request->salary_month_id)->where('user_id',$request->user_id)->first();
            if($importCsvDetail){
                $this->csvUpdate($request->salary_month_id,$request->user_id, $importCsvDetail);
            }
            return $this->sendResponse($fine, 200, ['Stored Successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Fine $fine)
    {
        try {
            return $this->sendResponse($fine, 200, ['Data get successfully,'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fine $fine)
    {
        try {
            $this->validate($request, [
                'user_id' => 'required',
                'amount' => 'required',
                'date' => 'required',
                'salary_month_id' => 'required',
            ]);
            $fine->update($request->only([
                'user_id',
                'amount',
                'details',
                'date',
                'salary_month_id'
            ]));
            $importCsvDetail = ImportCsvDetail::where('salary_month_id',$request->salary_month_id)->where('user_id',$request->user_id)->first();
            if($importCsvDetail){
                $this->csvUpdate($request->salary_month_id,$request->user_id, $importCsvDetail);
            }
            return $this->sendResponse($fine, 200, ['Updated successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }


    public function csvUpdate($salary_month_id, $user_id, $importCsvDetail)
    {
        $fine = Fine::where('salary_month_id',$salary_month_id)->where('user_id',$user_id)->sum('amount');
        if($importCsvDetail){
            $importCsvDetail->fine_deduction = $fine;
            $importCsvDetail->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fine $fine)
    {
        try {
            $fine->delete();
            return $this->sendResponse(null, 200, ['Record deleted successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }
}
