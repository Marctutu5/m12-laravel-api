<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fissurial;
use App\Models\Attack;
use Illuminate\Http\Request;

class FissurialAttackController extends Controller
{
    /**
     * Display a listing of the relationships.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $relationships = \DB::table('fissurials_attacks')->get();
        return response()->json($relationships);
    }

    /**
     * Display the specified relationship.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $relationship = \DB::table('fissurials_attacks')->find($id);
        if (!$relationship) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($relationship);
    }

    /**
     * Display relationships filtered by fissurial_id.
     *
     * @param  int  $fissurial_id
     * @return \Illuminate\Http\Response
     */
    public function getByFissurial($fissurial_id)
    {
        $fissurial = Fissurial::with('attacks')->find($fissurial_id);
    
        if (!$fissurial) {
            return response()->json(['message' => 'Fissurial not found'], 404);
        }
    
        return response()->json($fissurial);
    }
    
    /**
     * Display relationships filtered by attack_id.
     *
     * @param  int  $attack_id
     * @return \Illuminate\Http\Response
     */
    public function getByAttack($attack_id)
    {
        $attacks = Attack::with('fissurials')->find($attack_id);
    
        if (!$attacks) {
            return response()->json(['message' => 'Attack not found'], 404);
        }
    
        return response()->json($attacks);
    }
    
}
