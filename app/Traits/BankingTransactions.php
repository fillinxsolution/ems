<?php

namespace App\Traits;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;

trait BankingTransactions {

	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateBalance($account, $amount, $transactionType, $category)
    {

    	$transaction = Transaction::create([
    		'date' 		=> Carbon::now(),
    		'type' 		=> $transactionType,
    		'category' 	=> $category,
    		'account_id'=> $account,
    		'amount' 	=> $amount
    	]);
        $account = Account::find($account);
        switch ($transactionType) {
            case 'Incoming':
                $account->balance += $amount;
                break;
            case 'Outgoing':
                $account->balance -= $amount;
                break;
            default:
                break;
        }
        $account->save();
        return $transaction;
    }
}
