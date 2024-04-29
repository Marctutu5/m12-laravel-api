<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPosition;

class UserPositionController extends Controller
{
    /**
     * Display the position of the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $userPosition = $request->user()->position;  // Accede directamente a la posición del usuario autenticado

        if (!$userPosition) {
            return response()->json(['message' => 'Position not found'], 404);
        }

        return response()->json($userPosition);
    }

    /**
     * Update the position of the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'x' => 'required|integer',
            'y' => 'required|integer',
            'scene' => 'required|integer'
        ]);

        $userPosition = $request->user()->position;  // Accede a la posición del usuario autenticado

        if (!$userPosition) {
            return response()->json(['message' => 'Position not found'], 404);
        }

        $userPosition->update([
            'x' => $request->x,
            'y' => $request->y,
            'scene' => $request->scene
        ]);

        return response()->json(['message' => 'Position updated', 'position' => $userPosition]);
    }
}
