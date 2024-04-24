<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\PlaceStoreRequest;
use App\Http\Requests\PlaceUpdateRequest;

use App\Models\Place;
use App\Models\File;
use App\Models\Favorite;
use App\Http\Resources\PlaceResource;
use App\Http\Resources\PaginateCollection;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Place::class);

        $query = Place::withCount(['favorites','reviews']);

        // Filters
        if ($name = $request->get('name')) {
            $query->where('name', 'like', "%{$name}%");
        }

        if ($description = $request->get('description')) {
            $query->where('description', 'like', "%{$description}%");
        }

        if ($visibility = $request->get('visibility')) {
            $query->where('visibility_id', $visibility); 
        }
        
        if ($author = $request->get('author')) {
            $query->where('author_id', $author); 
        }

        // Pagination
        $paginate = $request->get('paginate', 0);
        $data = $paginate ? $query->paginate() : $query->get();
        
        return response()->json([
            'success' => true,
            'data'    => new PaginateCollection($data, PlaceResource::class)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PlaceStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceStoreRequest $request)
    {
        $this->authorize('create', Place::class);

        $validatedData = $request->validated();
        $upload        = $request->file('upload');

        $file = new File();
        $fileOk = $file->diskSave($upload);

        if ($fileOk) {
            Log::debug("Saving post at DB...");
            $place = Place::create([
                'name'          => $validatedData['name'],
                'description'   => $validatedData['description'],
                'file_id'       => $file->id,
                'latitude'      => $validatedData['latitude'],
                'longitude'     => $validatedData['longitude'],
                'visibility_id' => $validatedData['visibility'],
                'author_id'     => auth()->user()->id,
            ]);
            Log::debug("DB storage OK");
            return response()->json([
                'success' => true,
                'data'    => new PlaceResource($place)
            ], 201);
        } else {
            return response()->json([
                'success'  => false,
                'message' => 'Error uploading file'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $place = Place::find($id);
        
        if ($place) {
            $this->authorize('view', $place);
            $place->loadCount(['favorites','reviews']);
            return response()->json([
                'success' => true,
                'data'    => new PlaceResource($place)
            ], 200);
        } else {
            return response()->json([
                'success'  => false,
                'message' => 'File not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PlaceUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_workaround(PlaceUpdateRequest $request, $id)
    {
        // File upload workaround
        return $this->update($request, $id);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  PlaceUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlaceUpdateRequest $request, $id)
    {
        $place = Place::find($id);

        if (empty($place)) {
            return response()->json([
                'success'  => false,
                'message' => 'Place not found'
            ], 404);
        }

        $this->authorize('update', $place);
        $validatedData = $request->validated();
        $upload        = $request->file('upload');

        if (is_null($upload) || $place->file->diskSave($upload)) {
            Log::debug("Updating DB...");
            if (!empty($validatedData['name'])) {
                $place->name = $validatedData['name'];
            }
            if (!empty($validatedData['description'])) {
                $place->description = $validatedData['description'];
            }
            if (!empty($validatedData['latitude'])) {
                $place->latitude = $validatedData['latitude'];
            }
            if (!empty($validatedData['longitude'])) {
                $place->longitude = $validatedData['longitude'];
            }
            if (!empty($validatedData['visibility'])) {
                $place->visibility_id = $validatedData['visibility'];
            }
            $place->save();
            Log::debug("DB storage OK");
            return response()->json([
                'success' => true,
                'data'    => new PlaceResource($place)
            ], 200);
        } else {
            return response()->json([
                'success'  => false,
                'message' => 'Error uploading file'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $place = Place::find($id);

        if (empty($place)) {
            return response()->json([
                'success'  => false,
                'message' => 'Place not found'
            ], 404);
        }

        $this->authorize('delete', $place);
        $place->delete();
        $place->file->diskDelete();
        
        return response()->json([
            'success' => true,
            'data'    => new PlaceResource($place)
        ], 200);
    }


    /**
     * Add favorite
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function favorite($id) 
    {
        try {
            $favorite = Favorite::create([
                'user_id'  => auth()->user()->id,
                'place_id' => $id
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Favorite already exists"
            ], 400); 
        }
        
        return response()->json([
            'success' => true,
            'data'    => $favorite
        ], 200);
    }

    /**
     * Undo favorite
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unfavorite($id)
    {
        $favorite = Favorite::where([
            ['user_id', '=', auth()->user()->id],
            ['place_id', '=', $id],
        ])->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'success' => true,
                'data'    => $favorite
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Favorite not exists"
            ], 404); 
        }
    }
}
