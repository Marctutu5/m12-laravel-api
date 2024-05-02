<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fissurial;
use Illuminate\Http\Request;

class FissurialController extends Controller
{
    /**
     * Display a listing of the fissurials.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fissurials = Fissurial::all();
        return response()->json($fissurials);
    }

    /**
     * Display the specified fissurial.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fissurial = Fissurial::find($id);

        if (!$fissurial) {
            return response()->json(['message' => 'Fissurial not found'], 404);
        }

        return response()->json($fissurial);
    }
}
