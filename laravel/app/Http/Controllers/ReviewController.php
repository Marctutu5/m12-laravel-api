<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ReviewStoreRequest;

class ReviewController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Review::class, 'review');
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @param  int  $pid
     * @return \Illuminate\Http\Response
     */
    public function create($pid)
    {
        return view("reviews.create");
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
        // Validar dades del formulari
        $validatedData = $request->validated();

        // Desar dades a BD
        Log::debug("Saving review at DB...");
        $review = Review::create([
            "review"    => $validatedData["review"],
            "place_id"  => $pid,
            "author_id" => auth()->user()->id,
        ]);
        \Log::debug("DB storage OK");

        // Patró PRG amb missatge d'èxit
        return redirect()->back()
            ->with("success", __(":resource successfully saved", [
                "resource" => __("Review")
            ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $pid
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy($pid, Review $review)
    {
        if ($pid != $review->place_id) {
            // Patró PRG amb missatge d'error
            return redirect()->back()
                ->with("error", __("Another place review..."));
        }

        // Eliminar ressenya
        $review->delete();

        // Patró PRG amb missatge d'èxit
        return redirect()->back()
            ->with("success", __(":resource successfully deleted", [
                "resource" => __("Review")
            ]));
    }
}
