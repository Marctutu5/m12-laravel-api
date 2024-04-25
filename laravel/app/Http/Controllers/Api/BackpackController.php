<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Backpack;
use App\Models\Item;
use Illuminate\Http\Request;

class BackpackController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer'
        ]);

        $user = $request->user();
        $item = Item::findOrFail($request->item_id);
        $backpack = Backpack::firstOrCreate([
            'user_id' => $user->id,
            'item_id' => $item->id
        ], [
            'quantity' => 0
        ]);

        if ($request->quantity > 0) {
            $backpack->increment('quantity', $request->quantity);
        } elseif ($request->quantity < 0) {
            $newQuantity = $backpack->quantity + $request->quantity;
            if ($newQuantity < 0) {
                return response()->json(['message' => 'Not enough items to remove'], 422);
            }
            $backpack->quantity = $newQuantity;
            $backpack->save();
        }

        return response()->json([
            'message' => 'Backpack updated successfully',
            'backpack' => $backpack
        ]);
    }

    public function show(Request $request)
    {
        $user = $request->user();
    
        $backpackItems = Backpack::where('user_id', $user->id)
                                 ->with('item')
                                 ->get();
    
        if ($backpackItems->isEmpty()) {
            return response()->json(['message' => 'No items found for this user'], 404);
        }
    
        return response()->json($backpackItems);
    }
    

    public function index()
    {
        $backpacks = Backpack::with(['user', 'item'])->get();
        return response()->json($backpacks);
    }
}
