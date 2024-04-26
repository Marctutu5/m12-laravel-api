<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

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
     * Store a new listing.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0'
        ]);

        $listing = new Listing([
            'seller_id' => $request->user()->id,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);
        $listing->save();

        return response()->json(['message' => 'Listing created successfully', 'listing' => $listing]);
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
     * Remove the specified listing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $listing = Listing::find($id);

        if (!$listing) {
            return response()->json(['message' => 'Listing not found'], 404);
        }

        $listing->delete();
        return response()->json(['message' => 'Listing deleted successfully']);
    }
}
