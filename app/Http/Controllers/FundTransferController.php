<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\FundTransfer;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FundTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:transfer-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:transfer-create|transfer-edit', ['only' => ['store']]);
        $this->middleware('permission:transfer-edit', ['only' => ['update']]);
        $this->middleware('permission:transfer-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $fundTrasfer = FundTransfer::with('accountFrom','accountTo.user')
            ->search(($request->search) ? $request->search : '')
            ->paginate(($request->limit) ? $request->limit : 10);
            return $this->sendResponse($fundTrasfer, 200, ['Funds List'], true);
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
        try {
            $user = auth()->user();
            $user->load('accounts');
            $accounts = Account::where('user_id', '!=', $user->id)->get();
            $data = [
                'user' => $user,
                'accounts' => $accounts
            ];
            return $this->sendResponse($data, 200, ['Funds List'], true);
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
            $account_from = Account::where('id', $request->account_from)->first();
            if($account_from->balance < $request->amount){
                return $this->sendResponse(null, 500, ['Amount is greater then account balance'], false);
            }
            $transfer = FundTransfer::create($request->all());
            $transfer->updateBalance($request->account_from, $request->amount, 'Outgoing', 'Transfer');
            $transfer->updateBalance($request->account_to, $request->amount, 'Incoming', 'Transfer');
            return $this->sendResponse($transfer, 200, ['Fund Transfer Successfully'], true);
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
    public function show(FundTransfer $fundTransfer)
    {
        try {
            return $this->sendResponse($fundTransfer, 200, ['Funds Transfer Details'], true);
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
    public function edit(FundTransfer $fundTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FundTransfer $fundTransfer)
    {
        try {
            if ($fundTransfer->amount != $request->amount) {
                if ($request->amount > $fundTransfer->amount) {
                    $extra = $request->amount - $fundTransfer->amount;
                    $fundTransfer->updateBalance($fundTransfer->account_from, $extra, 'Outgoing', 'Transfer');
                    $fundTransfer->updateBalance($fundTransfer->account_to, $extra, 'Incoming', 'Transfer');
                }
                if ($request->amount < $fundTransfer->amount) {
                    $less = $request->amount - $fundTransfer->amount;
                    $fundTransfer->updateBalance($fundTransfer->account_from, $less, 'Incoming', 'Transfer');
                    $fundTransfer->updateBalance($fundTransfer->account_to, $less, 'Outgoing', 'Transfer');
                }
            }
            $fundTransfer->update($request->all());
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
    public function destroy(FundTransfer $fundTransfer)
    {
        //
    }
}
