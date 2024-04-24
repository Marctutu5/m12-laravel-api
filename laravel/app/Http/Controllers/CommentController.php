<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CommentStoreRequest;

class CommentController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Comment::class, 'comment');
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @param  int  $pid
     * @return \Illuminate\Http\Response
     */
    public function create($pid)
    {
        return view("comments.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $pid
     * @param  CommentStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store($pid, CommentStoreRequest $request)
    {
        // Validar dades del formulari
        $validatedData = $request->validated();

        // Desar dades a BD
        Log::debug("Saving comment at DB...");
        $comment = Comment::create([
            "comment"    => $validatedData["comment"],
            "post_id"  => $pid,
            "author_id" => auth()->user()->id,
        ]);
        \Log::debug("DB storage OK");

        // Patró PRG amb missatge d'èxit
        return redirect()->back()
            ->with("success", __(":resource successfully saved", [
                "resource" => __("Comment")
            ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $pid
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($pid, Comment $comment)
    {
        if ($pid != $comment->post_id) {
            // Patró PRG amb missatge d'error
            return redirect()->back()
                ->with("error", __("Another post comment..."));
        }

        // Eliminar
        $comment->delete();

        // Patró PRG amb missatge d'èxit
        return redirect()->back()
            ->with("success", __(":resource successfully deleted", [
                "resource" => __("Comment")
            ]));
    }
}
