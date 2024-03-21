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
            $qualifications = SalaryMonth::where('status', 1)->get();
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
