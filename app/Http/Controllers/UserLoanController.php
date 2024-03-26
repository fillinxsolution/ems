<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\User;
use App\Models\UserLoan;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserLoanController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:user-loan-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-loan-create|account-edit', ['only' => ['store']]);
        $this->middleware('permission:user-loan-edit', ['only' => ['update']]);
        $this->middleware('permission:user-loan-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = UserLoan::with('user')
            ->search(($request->search) ? $request->search : '')
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
                'purpose' => 'required',
                'installments' => 'required',
                'transferred_at' => 'required',
                'status' => 'required',
                'user_id' => 'required',
            ]);

            $userLoan = UserLoan::create($request->only([
                'amount',
                'installments',
                'transferred_at',
                'status',
                'user_id',
            ]));

            return $this->sendResponse($userLoan, 200, ['Stored Successfully.'], true);
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
    public function show(UserLoan $userLoan)
    {
        try {
            return $this->sendResponse($userLoan, 200, ['Data get successfully,'], true);
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
    public function update(Request $request, UserLoan $userLoan)
    {
        try {
            $this->validate($request, [
                'amount' => 'required',
                'purpose' => 'required',
                'installments' => 'required',
                'transferred_at' => 'required',
                'status' => 'required',
            ]);
            $userLoan->update($request->only([
                'amount',
                'installments',
                'transferred_at',
                'status',
                'user_id',
            ]));

            return $this->sendResponse($userLoan, 200, ['Updated successfully.'], true);
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
    public function destroy(UserLoan $userLoan)
    {
        try {
            $userLoan->delete();
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
