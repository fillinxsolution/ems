<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:account-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:account-create|account-edit', ['only' => ['store']]);
        $this->middleware('permission:account-edit', ['only' => ['update']]);
        $this->middleware('permission:account-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // $user = auth()->user();
            $accounts = Account::with('bank')
            ->search(($request->search) ? $request->search : '')
            ->paginate(($request->limit) ? $request->limit : 10);
            return $this->sendResponse($accounts, 200, ['Accounts List'], true);
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
        try {
            $this->validate($request, [
                'title' => 'required',
                'account_number' => 'required|unique:accounts,account_number',
                'bank_id' => 'required',
                'status' => 'required',
                'balance' => 'required'
            ]);
            $user = auth()->user();
            $account = $user->accounts()->create($request->all());

            return $this->sendResponse($account, 200, ['Account Created Successfully'], true);
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
    public function show(Account $account)
    {
        try {
            $account->load('transactions', 'bank', 'expenses', 'user');
            return $this->sendResponse($account, 200, ['Account Details'], true);
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
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $this->validate($request, [
            'title' => 'required',
            'account_number' => 'required|unique:accounts,account_number,'.$account->id .'ID',
            'status' => 'required'
        ]);
        try {
            if (!$account) {
                return $this->sendResponse(null, 500, ['Please select a valid account'], false);
            }
            $account->update([
                'title' => $request->title,
                'account_number' => $request->account_number,
                'status' => $request->status
            ]);
            return $this->sendResponse($account, 200, ['Account Updated Successfully'], true);
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
    public function destroy(Account $account)
    {
        try {
            $user = auth()->user();
            if ($user->id == $account->user_id) {
                $account->delete();
                return $this->sendResponse(null, 200, ['Account Deleted Successfully'], true);
            } else {
                return $this->sendResponse(null, 500, ['You have no permission for this task'], false);
            }
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return $this->sendResponse(null, 500, [$e->getMessage()], false);
        }
    }
}
