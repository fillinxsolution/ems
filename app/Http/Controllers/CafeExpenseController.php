<?php

namespace App\Http\Controllers;

use App\Models\CafeExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CafeExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $cafes = CafeExpense::with('user')->search(($request->search) ? $request->search : '')
            ->paginate(($request->limit) ? $request->limit : 10);
            return $this->sendResponse($cafes, 200, ['Get List Successfully.'], true);
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
                'cafe_id' => 'required',
                'amount' => 'required',
                'quantity' => 'required',
                'date' => 'required',
                'salary_month_id' => 'required',
            ]);
            $cafe = CafeExpense::create($request->only([
                'user_id',
                'cafe_id',
                'amount',
                'quantity',
                'details',
                'date',
                'salary_month_id'
            ]));

            return $this->sendResponse($cafe, 200, ['Stored Successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CafeExpense $cafeExpense)
    {
        try {
            return $this->sendResponse($cafeExpense, 200, ['Data get successfully,'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CafeExpense $cafeExpense)
    {
        try {
            $this->validate($request, [
                'user_id' => 'required',
                'cafe_id' => 'required',
                'amount' => 'required',
                'quantity' => 'required',
                'date' => 'required',
                'salary_month_id' => 'required',
            ]);
            $cafeExpense->update($request->only([
                'user_id',
                'cafe_id',
                'amount',
                'quantity',
                'details',
                'date',
                'salary_month_id'
            ]));
            return $this->sendResponse($cafeExpense, 200, ['Updated successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CafeExpense $cafeExpense)
    {
        try {
            $cafeExpense->delete();
            return $this->sendResponse(null, 200, ['Record deleted successfully.'], true);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }
}
