<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;

class WalletController extends Controller
{
    /**
     * Display the wallet of the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $wallet = $request->user()->wallet;  // Directamente accede a la wallet a través del usuario autenticado

        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        return response()->json($wallet);
    }

    /**
     * Update the wallet of the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'coins' => 'required|integer'
        ]);

        $wallet = $request->user()->wallet;  // Accede a la wallet del usuario autenticado

        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        $coins = $request->coins;
        
        if ($coins > 0) {
            $wallet->addCoins($coins);
        } else {
            if (!$wallet->subtractCoins(abs($coins))) {
                return response()->json(['message' => 'Insufficient coins'], 400);
            }
        }

        return response()->json(['message' => 'Wallet updated', 'wallet' => $wallet]);
    }
}
