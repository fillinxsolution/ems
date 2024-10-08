<?php

namespace App\Http\Controllers;

use App\Models\UserLoan;
use App\Models\ImportCsvDetail;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstallmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $installments = Installment::with('loan.user')
                ->search(($request->search) ? $request->search : '')
                ->paginate(($request->limit) ? $request->limit : 10);
            return $this->sendResponse($installments, 200, ['Get List Successfully.'], true);
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
                'user_loan_id' => 'required',
                'salary_month_id' => 'required',
            ]);
            $installment = Installment::create($request->only([
                'user_loan_id',
                'amount',
                'date',
                'status',
                'salary_month_id',
            ]));

            $paidInstallment = Installment::where('user_loan_id',$request->user_loan_id)->sum('amount');
            $loan = UserLoan::where('id',$request->user_loan_id)->first();
            if ($paidInstallment != $loan->amount){
                $remainingAmount =  (float) $loan->amount - (float) $paidInstallment;
                $loan->remaining_amount = $remainingAmount;
                $loan->paid_amount = $paidInstallment;
                $loan->save();
            }
            $importCsvDetail = ImportCsvDetail::where('salary_month_id', $request->salary_month_id)->where('user_id', $request->user_id)->first();
            if ($importCsvDetail) {
                $this->csvUpdate($request->salary_month_id, $request->user_loan_id ,$request->user_id, $importCsvDetail);
            }

            return $this->sendResponse($installment, 200, ['Stored Successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Installment $installment)
    {
        try {
            return $this->sendResponse($installment, 200, ['Data get successfully,'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Installment $installment)
    {
        try {
            $this->validate($request, [
                'user_id' => 'required',
                'amount' => 'required',
                'date' => 'required',
                'salary_month_id' => 'required',
            ]);
            $installment->update($request->only([
                'user_loan_id',
                'amount',
                'date',
                'status',
                'salary_month_id',
            ]));
            $paidInstallment = Installment::where('user_loan_id',$request->user_loan_id)->sum('amount');
            $loan = UserLoan::where('id',$request->user_loan_id)->first();
            if ($loan){
                $remainingAmount =  (float) $loan->amount - (float) $paidInstallment;
                if ($paidInstallment != $loan->amount){
                    $loan->remaining_amount = $remainingAmount;
                    $loan->paid_amount = $paidInstallment;
                    $loan->save();
                }
            }
            $importCsvDetail = ImportCsvDetail::where('salary_month_id', $request->salary_month_id)->where('user_id', $request->user_id)->first();
            if ($importCsvDetail) {
                $this->csvUpdate($request->salary_month_id, $request->user_loan_id, $request->user_id, $importCsvDetail);
            }

            return $this->sendResponse($installment, 200, ['Updated successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Installment $installment)
    {
        try {
            $installment->delete();
            return $this->sendResponse(null, 200, ['Record deleted successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    public function csvUpdate($salary_month_id,$user_loan_id, $user_id, $importCsvDetail)
    {
        $instalment = Installment::where('salary_month_id', $salary_month_id)->where('user_loan_id', $user_loan_id)->sum('amount');
        if ($importCsvDetail) {
            $importCsvDetail->loan_deduction = $instalment;
            $importCsvDetail->save();
        }
    }
}
