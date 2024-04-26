<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display all transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::with(['buyer', 'seller', 'item'])->get();
        return response()->json($transactions);
    }

    /**
     * Store a new transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric'
        ]);

        $transaction = new Transaction([
            'buyer_id' => $request->user()->id,
            'seller_id' => $request->seller_id,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);
        $transaction->save();

        return response()->json(['message' => 'Transaction completed successfully', 'transaction' => $transaction]);
    }
}
