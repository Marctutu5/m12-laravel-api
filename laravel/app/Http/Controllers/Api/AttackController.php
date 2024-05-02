<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attack; // Asegúrate de que el modelo Attack está creado y configurado correctamente
use Illuminate\Http\Request;

class AttackController extends Controller
{
    /**
     * Display a listing of the attacks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attacks = Attack::all(); // Obtiene todos los ataques desde la base de datos
        return response()->json($attacks); // Devuelve los ataques en formato JSON
    }

    /**
     * Display the specified attack.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attack = Attack::find($id); // Busca el ataque por su ID

        if (!$attack) {
            return response()->json(['message' => 'Attack not found'], 404); // Si no se encuentra, devuelve un error 404
        }

        return response()->json($attack); // Si se encuentra, devuelve el ataque en formato JSON
    }
}
