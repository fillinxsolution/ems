<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExpenseTypeController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:expense-type-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:expense-type-create|expense-type-edit', ['only' => ['store']]);
        $this->middleware('permission:expense-type-edit', ['only' => ['update']]);
        $this->middleware('permission:expense-type-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $expenseTypes = ExpenseType::search(($request->search) ? $request->search : '')
            ->paginate(($request->limit) ? $request->limit : 10);
            return $this->sendResponse($expenseTypes, 200, ['Expense Type List'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }


    public function filter(Request $request)
    {
        try {
            $user = auth('sanctum')->user();

            $date = ($request->date) ? strtotime($request->date) : null;
            $start_date = ($request->start_date) ? strtotime($request->start_date) : null;
            $end_date = ($request->end_date) ? strtotime($request->end_date) : null;

            if (($user->hasRole('Super Admin') || $user->hasRole('Admin')) && $user->is_admin = 1)
            {
                $expenseType = Expense::with('expenseType')
                ->when($date, function($query) use ($date) {
                    $query->where('date',$date);
                })
                ->when($request->expense_type_id, function($query) use ($request) {
                    $query->where('expense_type_id',$request->expense_type_id);
                })
                ->when($start_date && $end_date, function($query) use ($start_date, $end_date) {
                    $query->whereBetween('date', [$start_date, $end_date]);
                })
                ->get();
            }
            if ($user->hasRole('HR') && $user->is_admin = 1)
            {
                $expenseType = Expense::with('expenseType')
                    ->when($date, function($query) use ($date) {
                        $query->where('date',$date);
                    })
                    ->when($request->expense_type_id, function($query) use ($request) {
                        $query->where('expense_type_id',$request->expense_type_id);
                    })
                    ->when($start_date && $end_date, function($query) use ($start_date, $end_date) {
                        $query->whereBetween('date', [$start_date, $end_date]);
                    })->where('user_id',$user->id)
                    ->get();
            }
            // $expenseType = $expenseType->paginate(($request->limit) ? $request->limit : 10);

            return $this->sendResponse($expenseType, 200, ['Expense Type List'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }
    /**
     * Display all listing of the resource.
     */
    public function list()
    {
        try {
            $expenseTypes = ExpenseType::where('status','1')->get();
            return $this->sendResponse($expenseTypes, 200, ['Expense Type List'], true);
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
            'name' => 'required',
            'status' => 'required',
            'details' => 'required',
        ]);
        try {
            $expenseType = ExpenseType::create($request->all());
            return $this->sendResponse($expenseType, 200, ['Expense Type Created Successfully'], true);
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
    public function show(ExpenseType $expenseType)
    {
        try {
            return $this->sendResponse($expenseType, 200, ['Expense Type Details'], true);
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
    public function edit(ExpenseType $expenseType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseType $expenseType)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
            'details' => 'required',
        ]);
        try {
            $expenseType->update($request->all());
            return $this->sendResponse($expenseType, 200, ['Expense Type Updated Successfully'], true);
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
    public function destroy(ExpenseType $expenseType)
    {
        try {
            $expenseType->delete();
            return $this->sendResponse(null, 200, ['Expense Type Deleted Successfully'], true);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }
}
