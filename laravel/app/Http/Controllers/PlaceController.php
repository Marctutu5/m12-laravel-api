<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Place;
use App\Models\File;
use App\Models\Favorite;

use App\Http\Requests\PlaceStoreRequest;
use App\Http\Requests\PlaceUpdateRequest;

class PlaceController extends Controller
{
    private bool $_pagination = true;

    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Place::class, 'place');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Order and count
        $collectionQuery = Place::withCount('favorited')
            ->orderBy('created_at', 'desc');

        // Filter?
        if ($search = $request->get('search')) {
            $collectionQuery->where('description', 'like', "%{$search}%");
        }
        
        // Pagination
        $places = $this->_pagination 
            ? $collectionQuery->paginate(5)->withQueryString() 
            : $collectionQuery->get();
        
        return view("places.index", [
            "places" => $places,
            "search" => $search
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("places.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PlaceStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceStoreRequest $request)
    {
        // Validar dades del formulari
        $validatedData = $request->validated();
        
        // Obtenir dades del formulari
        $name        = $request->get('name');
        $description = $request->get('description');
        $upload      = $request->file('upload');
        $latitude    = $request->get('latitude');
        $longitude   = $request->get('longitude');
        $visibility  = $request->get('visibility');

        // Desar fitxer al disc i inserir dades a BD
        $file = new File();
        $fileOk = $file->diskSave($upload);

        if ($fileOk) {
            // Desar dades a BD
            Log::debug("Saving place at DB...");
            $place = Place::create([
                'name'          => $name,
                'description'   => $description,
                'file_id'       => $file->id,
                'latitude'      => $latitude,
                'longitude'     => $longitude,
                'author_id'     => auth()->user()->id,
                'visibility_id' => $visibility,
            ]);
            Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('places.show', $place)
                ->with('success', __(':resource successfully saved', [
                    'resource' => __('Place')
                ]));
        } else {
            Log::debug("Disk storage FAILS");
            // Patró PRG amb missatge d'error
            return redirect()->route("places.create")
                ->with('error', __('ERROR uploading file'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show(Place $place)
    {
        // Count
        $place->loadCount('favorited','reviews');

        return view("places.show", [
            'place'   => $place,
            'file'    => $place->file,
            'author'  => $place->user,
            'numFavs' => $place->favorited_count,
            'numReviews' => $place->reviews_count,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function edit(Place $place)
    {
        return view("places.edit", [
            'place'  => $place,
            'file'   => $place->file,
            'author' => $place->user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PlaceUpdateRequest  $request
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function update(PlaceUpdateRequest $request, Place $place)
    {
        // Validar dades del formulari
        $validatedData = $request->validated();
        
        // Desar fitxer (opcional)
        $upload = $request->file('upload');

        if (is_null($upload) || $place->file->diskSave($upload)) {
            // Actualitzar dades a BD
            Log::debug("Updating DB...");
            if ($name = $request->get('name')) {
                $place->name = $name;
            }
            if ($description = $request->get('description')) {
                $place->description = $description;
            }
            if ($latitude = $request->get('latitude')) {
                $place->latitude = $latitude;
            }
            if ($longitude = $request->get('longitude')) {
                $place->longitude = $longitude;
            }
            if ($visibility = $request->get('visibility')) {
                $place->visibility_id = $visibility;
            }
            $place->save();
            Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('places.show', $place)
                ->with('success', __(':resource successfully saved', [
                    'resource' => __('Place')
                ]));
        } else {
            // Patró PRG amb missatge d'error
            return redirect()->route("places.create")
                ->with('error', __('ERROR uploading file'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function destroy(Place $place)
    {
        // Eliminar place de BD
        $place->delete();
        // Eliminar fitxer associat del disc i BD
        $place->file->diskDelete();
        // Patró PRG amb missatge d'èxit
        return redirect()->route("places.index")
            ->with('success', __(':resource successfully deleted', [
                'resource' => __('Place')
            ]));
    }

    /**
     * Confirm specified resource deletion from storage.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function delete(Place $place)
    {
        return view("places.delete", [
            'place' => $place
        ]);
    }

    /**
     * Add favorite place
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function favorite(Place $place) 
    {
        $fav = Favorite::create([
            'user_id'  => auth()->user()->id,
            'place_id' => $place->id
        ]);

        return redirect()->back()
            ->with('success', __(':resource successfully saved', [
                'resource' => __('Favorite')
            ]));
    }

    /**
     * Undo favorite
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function unfavorite(Place $place) 
    {
        $fav = Favorite::where([
            ['user_id',  '=', auth()->user()->id],
            ['place_id', '=', $place->id],
        ])->first();
        
        $fav->delete();
        
        return redirect()->back()
            ->with('success', __(':resource successfully deleted', [
                'resource' => __('Favorite')
            ]));
    }
}
