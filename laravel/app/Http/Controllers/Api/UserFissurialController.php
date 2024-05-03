<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserFissurial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFissurialController extends Controller
{
    public function index()
    {
        // Muestra todos los registros de UserFissurial incluyendo detalles relacionados
        $userFissurials = UserFissurial::with(['user', 'fissurial'])->get();
        return response()->json($userFissurials);
    }

    public function show(Request $request)
    {
        // Muestra los registros de UserFissurial del usuario autenticado
        $user = $request->user();
        $userFissurials = UserFissurial::where('user_id', $user->id)->with('fissurial')->get();

        if ($userFissurials->isEmpty()) {
            return response()->json(['message' => 'No fissurials found for this user'], 404);
        }

        return response()->json($userFissurials);
    }

    public function update(Request $request)
    {
        // Actualiza el current_life del Fissurial del usuario autenticado
        $request->validate([
            'fissurials_id' => 'required|exists:fissurials,id',
            'current_life' => 'required|integer'
        ]);

        $user = $request->user();
        $userFissurial = UserFissurial::where('user_id', $user->id)
                                      ->where('fissurials_id', $request->fissurials_id)
                                      ->firstOrFail();

        $userFissurial->current_life = $request->current_life;
        $userFissurial->save();

        return response()->json([
            'message' => 'User Fissurial updated successfully',
            'userFissurial' => $userFissurial
        ]);
    }
}
