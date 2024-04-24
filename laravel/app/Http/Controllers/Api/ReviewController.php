<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Review;
use App\Http\Resources\ReviewResource;
use App\Http\Requests\ReviewStoreRequest;
use App\Http\Resources\PaginateCollection;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $pid
     * @return \Illuminate\Http\Response
     */
    public function index($pid, Request $request)
    {
        $this->authorize('viewAny', Review::class);

        $query = Review::where("place_id", "=", $pid);
        $paginate = $request->query('paginate', 0);
        $data = $paginate ? $query->paginate() : $query->get();
        
        return response()->json([
            'success' => true,
            'data'    => new PaginateCollection($data, ReviewResource::class)
        ], 200);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $pid
     * @param  ReviewStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store($pid, ReviewStoreRequest $request)
    {
        $this->authorize('create', Review::class);

        $validatedData = $request->validated();
        
        $review = Review::where([
            ['place_id', '=', $pid],
            ['author_id', '=', auth()->user()->id],
        ])->first();
        
        if ($review) {
            return response()->json([
                'success'  => false,
                'message' => 'Review already created'
            ], 400);
        } else {
            Log::debug("Saving review at DB...");
            $review = Review::create([
                "review"    => $validatedData["review"],
                "place_id"  => $pid,
                "author_id" => auth()->user()->id,
            ]);
            \Log::debug("DB storage OK");
    
            return response()->json([
                'success' => true,
                'data'    => new ReviewResource($review)
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $pid
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($pid, $id)
    {
        $review = Review::where([
            ['id', '=', $id],
            ['place_id', '=', $pid],
        ])->first();
        
        if ($review) {
            $this->authorize('view', $review);
            return response()->json([
                'success' => true,
                'data'    => $review
            ], 200);
        } else {
            return response()->json([
                'success'  => false,
                'message' => 'Review not found'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $pid
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($pid, $id)
    {
        $review = Review::where([
            ['id', '=', $id],
            ['place_id', '=', $pid],
        ])->first();

        if ($review) {
            $this->authorize('delete', $review);
            $review->delete();
            return response()->json([
                'success' => true,
                'data'    => new ReviewResource($review)
            ], 200);
        } else {
            return response()->json([
                'success'  => false,
                'message' => 'Review not found'
            ], 404);
        }
    }
}
