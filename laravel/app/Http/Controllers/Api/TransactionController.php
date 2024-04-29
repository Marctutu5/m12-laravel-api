<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Listing;
use App\Models\User;

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
     * Store a new transaction: gestionar la compra de items.
     */
    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'quantity' => 'required|integer|min:1'
        ]);
    
        DB::beginTransaction();
        try {
            $listing = Listing::findOrFail($request->listing_id);
            if ($listing->quantity < $request->quantity) {
                return response()->json(['message' => 'Not enough stock'], 400);
            }
    
            $buyer = $request->user();
            $seller = User::findOrFail($listing->seller_id);
    
            if ($buyer->wallet->coins < $listing->price * $request->quantity) {
                return response()->json(['message' => 'Insufficient funds'], 400);
            }
    
            // Transferir monedas
            $buyer->wallet->subtractCoins($listing->price * $request->quantity);
            $seller->wallet->addCoins($listing->price * $request->quantity);
    
            // Crear transacción
            $transaction = new Transaction([
                'buyer_id' => $buyer->id,
                'seller_id' => $seller->id,
                'item_id' => $listing->item_id,
                'quantity' => $request->quantity,
                'price' => $listing->price * $request->quantity
            ]);
            $transaction->save();
    
            // Actualizar el listing
            $listing->quantity -= $request->quantity;
            $listing->save();
    
            if ($listing->quantity == 0) {
                $listing->delete();
            }
    
            DB::commit();
            return response()->json(['message' => 'Purchase successful', 'transaction' => $transaction]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Purchase failed', 'error' => $e->getMessage()], 500);
        }
    }    
}
