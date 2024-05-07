<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recharge;
use App\Models\User;
use Illuminate\Http\Request;

class RechargeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string|unique:recharges',
            'amount' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'coins_purchased' => 'required|integer',
            'payer_email' => 'required|email',
            'payer_name' => 'required|string'
        ]);

        $user = $request->user();
        
        $recharge = new Recharge([
            'user_id' => $user->id,
            'transaction_id' => $request->transaction_id,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'coins_purchased' => $request->coins_purchased,
            'payer_email' => $request->payer_email,
            'payer_name' => $request->payer_name
        ]);
        $recharge->save();

        return response()->json(['message' => 'Recharge successful', 'recharge' => $recharge]);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $recharges = Recharge::where('user_id', $user->id)
                             ->with('user')  // Suponiendo que tienes una relación 'user' definida en el modelo Recharge
                             ->get();
    
        if ($recharges->isEmpty()) {
            return response()->json(['message' => 'No recharges found'], 404);
        }
    
        return response()->json($recharges);
    }
    
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $recharge = Recharge::where('user_id', $user->id)->where('id', $id)->first();

        if (!$recharge) {
            return response()->json(['message' => 'Recharge not found'], 404);
        }

        return response()->json($recharge);
    }

}
