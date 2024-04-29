<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Backpack; // Importa el modelo Backpack correctamente
use App\Models\User; // Si también utilizas User

class ListingController extends Controller
{
    /**
     * Display a listing of all listings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listings = Listing::with(['seller', 'item'])->get();
        return response()->json($listings);
    }

    /**
     * Store a new listing: mover items del backpack al listing.
     */
    public function store(Request $request)
    {
        // Validación de la solicitud
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        try {
            $user = $request->user();

            // Verificar si ya existe un listado para el mismo artículo y vendedor
            $existingListing = Listing::where('seller_id', $user->id)
                ->where('item_id', $request->item_id)
                ->first();

            if ($existingListing) {
                return response()->json(['message' => 'Listing already exists for this item and seller'], 400);
            }

            // Crear el listado
            $listing = new Listing([
                'seller_id' => $user->id,
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'price' => $request->price
            ]);
            $listing->save();

            // Resto del código para manejar la transacción...

            DB::commit();
            return response()->json(['message' => 'Listing created successfully', 'listing' => $listing]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create listing', 'error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Display the specified listing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $listing = Listing::with(['seller', 'item'])->find($id);

        if (!$listing) {
            return response()->json(['message' => 'Listing not found'], 404);
        }

        return response()->json($listing);
    }

    /**
     * Cancel a listing: devolver items al backpack.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $listing = Listing::findOrFail($id);
            $listing->delete(); // Eliminamos la orden de listado

            DB::commit();
            return response()->json(['message' => 'Listing cancelled and items returned']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to cancel listing', 'error' => $e->getMessage()], 500);
        }
    }
}
