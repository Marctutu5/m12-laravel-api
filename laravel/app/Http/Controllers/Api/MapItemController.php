<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MapItem;

class MapItemController extends Controller
{
    /**
     * Display a listing of the map items.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mapItems = MapItem::all();
        return response()->json($mapItems);
    }

    /**
     * Display the specified map item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mapItem = MapItem::find($id);

        if (!$mapItem) {
            return response()->json(['message' => 'Map item not found'], 404);
        }

        return response()->json($mapItem);
    }

    /**
     * Update the specified map item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $mapItem = MapItem::find($id);

        if (!$mapItem) {
            return response()->json(['message' => 'Map item not found'], 404);
        }

        $mapItem->update($request->all());

        return response()->json(['message' => 'Map item updated', 'map_item' => $mapItem]);
    }
}
