<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserCollectedItem;

class UserCollectedItemController extends Controller
{
        /**
     * Display a listing of the collected items for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $collectedItems = $user->UsercollectedItems()->get();

        return response()->json($collectedItems);
    }
    /**
     * Display the specified user collected item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userCollectedItem = UserCollectedItem::find($id);

        if (!$userCollectedItem) {
            return response()->json(['message' => 'User collected item not found'], 404);
        }

        return response()->json($userCollectedItem);
    }

    /**
     * Update the specified user collected item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userCollectedItem = UserCollectedItem::find($id);

        if (!$userCollectedItem) {
            return response()->json(['message' => 'User collected item not found'], 404);
        }

        $userCollectedItem->update($request->all());

        return response()->json(['message' => 'User collected item updated', 'user_collected_item' => $userCollectedItem]);
    }
}
