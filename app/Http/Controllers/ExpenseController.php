<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $expense = Expense::with(
                ['user' => function($query) {
                    $query->select('id', 'name');
                }, 'account' => function($query) {
                    $query->select('id', 'title', 'account_number');
                }, 'expenseType' => function($query) {
                    $query->select('id', 'name');
                }]
            )->get();
            return $this->sendResponse($expense, 200, ['Expenses List'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'type' => 'required',
            'expense_type_id' => 'required',
            'account_id' => 'required',
            'status' => 'required',
            'details' => 'required',
            'amount' => 'required'
        ]);

        try {
            $user = auth()->user();
            $expense = $user->expenses()->create($request->all());
            if ($expense) {
                $expense->updateBalance($request->account_id, $request->amount, 'Outgoing', 'Transfer');
                return $this->sendResponse($expense, 200, ['Expense Created Successfully'], true);
            }
            return $this->sendResponse(null, 500, ['somthing wrong'], false);
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
    public function show(Expense $expense)
    {
        try {
            return $this->sendResponse($expense, 200, ['Expense Details'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'type' => 'required',
            'expense_type_id' => 'required',
            'account_id' => 'required',
            'status' => 'required',
            'details' => 'required',
            'amount' => 'required'
        ]);
        try {
            if ($expense->amount != $request->amount) {
                if ($request->amount > $expense->amount) {
                    $extra = $request->amount - $expense->amount;
                    $expense->updateBalance($expense->account_id, $extra, 'Outgoing', 'Transfer');
                }
                if ($request->amount < $expense->amount) {
                    $less = $request->amount - $expense->amount;
                    $expense->updateBalance($expense->account_id, $less, 'Incoming', 'Transfer');
                }
            }
            $expense->update($request->all());
            return $this->sendResponse($expense, 200, ['Expense Updated Successfully'], true);


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
    public function destroy(Expense $expense)
    {
        try {
            $expense->delete();
            return $this->sendResponse(null, 200, ['Expense Deleted Successfully'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }
}
