<?php

namespace App\Http\Controllers;

use App\Models\SalaryMonth;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SalaryMonthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $salaryMonth = SalaryMonth::all();
            return $this->sendResponse($salaryMonth, 200, ['Get List Successfully.'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    public function active()
    {
        try {
            $salaryMonth = SalaryMonth::where('status', '1')->get();
            if($salaryMonth){
                return $this->sendResponse($salaryMonth, 200, ['Get List Successfully.'], true);
            }else{
                return $this->sendResponse(null, 200, ['Sorry not salary month is active'], true);
            }

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
        $validator = Validator::make($request->all(), [
            'month' => 'required',
            'year' => 'required',
            'name' => 'required',
            'status' => 'required',
        ]);
        $validator->after(function ($validator) use ($request) {
            $exists = SalaryMonth::where('month', $request->month)
                ->where('year', $request->year)
                ->exists();
            if ($exists) {
                $validator->errors()->add('month', 'The combination of month and year already exists.');
            }
        });
        if ($validator->fails()) {
            return $this->sendResponse(null, 500, [$validator->errors()], false);
        }
        try {
            $salaryMonthStatus = SalaryMonth::where('status','1')->count();
            if ($salaryMonthStatus > 0)
            {
                return $this->sendResponse(null, 500, ['Please Close your previous salary month'], false);
            }
            $salaryMonth = SalaryMonth::create($request->only(['name', 'month', 'year', 'status']));
            return $this->sendResponse($salaryMonth, 200, ['Stored Successfully.'], true);
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
    public function update(Request $request, SalaryMonth $salaryMonth)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);
        try {
            $salaryMonthStatus = SalaryMonth::where('status','1')->count();
            if ($request->status == '0'){
                $salaryMonth->update($request->only(['status']));
            }
            if ($salaryMonthStatus >= 1 && $request->status != '0')
            {
                return $this->sendResponse(null, 500, ['Please Close your previous salary month'], false);
            }
            $salaryMonth->update($request->only(['status']));
            return $this->sendResponse($salaryMonth, 200, ['Updated Successfully.'], true);
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
    public function destroy(SalaryMonth $salaryMonth)
    {
        try {
            if($salaryMonth->status == '1'){
                $salaryMonth->delete();
                return $this->sendResponse(null, 200, ['Record deleted successfully.'], true);
            }else{
                return $this->sendResponse(null, 500, ['Salary month is not an active salary month.'], false);
            }

        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, ['error'=> $e->getMessage(),'message'=> 'Delete Associated Data First'], false);
        }
    }
}
