<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Post;
use App\Models\File;
use App\Models\Like;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;

class PostController extends Controller
{
    private bool $_pagination = true;

    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
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
        $collectionQuery = Post::withCount('liked')
            ->orderBy('created_at', 'desc');
        
        // Filter?
        if ($search = $request->get('search')) {
            $collectionQuery->where('body', 'like', "%{$search}%");
        }
        
        // Pagination
        $posts = $this->_pagination 
            ? $collectionQuery->paginate(5)->withQueryString() 
            : $collectionQuery->get();
        
        return view("posts.index", [
            "posts" => $posts,
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
        return view("posts.create");  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PostStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreRequest $request)
    {
        // Validar dades del formulari
        $validatedData = $request->validated();
        
        // Obtenir dades del formulari
        $body       = $request->get('body');
        $upload     = $request->file('upload');
        $latitude   = $request->get('latitude');
        $longitude  = $request->get('longitude');
        $visibility = $request->get('visibility');

        // Desar fitxer al disc i inserir dades a BD
        $file = new File();
        $fileOk = $file->diskSave($upload);

        if ($fileOk) {
            // Desar dades a BD
            Log::debug("Saving post at DB...");
            $post = Post::create([
                'body'      => $body,
                'file_id'   => $file->id,
                'latitude'  => $latitude,
                'longitude' => $longitude,
                'author_id' => auth()->user()->id,
                'visibility_id' => $visibility,
            ]);
            Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('posts.show', $post)
                ->with('success', __(':resource successfully saved', [
                    'resource' => __('Post')
                ]));
        } else {
            // Patró PRG amb missatge d'error
            return redirect()->route("posts.create")
                ->with('error', __('ERROR Uploading file'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        // Count
        $post->loadCount('liked','comments');

        return view("posts.show", [
            'post'     => $post,
            'file'     => $post->file,
            'author'   => $post->user,
            'numLikes' => $post->liked_count,
            'numComments' => $post->comments_count,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view("posts.edit", [
            'post'   => $post,
            'file'   => $post->file,
            'author' => $post->user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PostUpdateRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        // Validar dades del formulari
        $validatedData = $request->validated();
        
        // Desar fitxer (opcional)
        $upload = $request->file('upload');

        if (is_null($upload) || $post->file->diskSave($upload)) {
            // Actualitzar dades a BD
            Log::debug("Updating DB...");
            if ($body = $request->get('body')) {
                $post->body = $body;
            }
            if ($latitude = $request->get('latitude')) {
                $post->latitude = $latitude;
            }
            if ($longitude = $request->get('longitude')) {
                $post->longitude = $longitude;
            }
            if ($visibility = $request->get('visibility')) {
                $post->visibility_id = $visibility;
            }
            $post->save();
            Log::debug("DB storage OK");
            // Patró PRG amb missatge d'èxit
            return redirect()->route('posts.show', $post)
                ->with('success', __(':resource successfully saved', [
                    'resource' => __('Post')
                ]));
        } else {
            // Patró PRG amb missatge d'error
            return redirect()->route("posts.edit")
                ->with('error', __('ERROR Uploading file'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // Eliminar post de BD
        $post->delete();
        // Eliminar fitxer associat del disc i BD
        $post->file->diskDelete();
        // Patró PRG amb missatge d'èxit
        return redirect()->route("posts.index")
            ->with('success', __(':resource successfully deleted', [
                'resource' => __('Post')
            ]));
    }

    /**
     * Confirm specified resource deletion from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function delete(Post $post)
    {
        $this->authorize('delete', $post);
        
        return view("posts.delete", [
            'post' => $post
        ]);
    }

    /**
     * Add like
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function like(Post $post) 
    {
        $this->authorize('like', $post);

        $like = Like::create([
            'user_id'  => auth()->user()->id,
            'post_id' => $post->id
        ]);

        return redirect()->back()
            ->with('success', __(':resource successfully saved', [
                'resource' => __('Like')
            ]));
    }

    /**
     * Undo like
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function unlike(Post $post) 
    {
        $this->authorize('unlike', $post);

        $like = Like::where([
            ['user_id', '=', auth()->user()->id],
            ['post_id', '=', $post->id],
        ])->first();
        
        $like->delete();

        return redirect()->back()
            ->with('success', __(':resource successfully deleted', [
                'resource' => __('Like')
            ]));
    }
}
