<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;

use App\Models\Post;
use App\Models\File;
use App\Models\Like;
use App\Http\Resources\PostResource;
use App\Http\Resources\PaginateCollection;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Post::class);

        $query = Post::withCount(['likes','comments']);

        // Filters
        if ($body = $request->get('body')) {
            $query->where('body', 'like', "%{$body}%");
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
            'data'    => new PaginateCollection($data, PostResource::class)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PostStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreRequest $request)
    {
        $this->authorize('create', Post::class);

        $validatedData = $request->validated();
        $upload        = $request->file('upload');

        $file = new File();
        $fileOk = $file->diskSave($upload);

        if ($fileOk) {
            Log::debug("Saving post at DB...");
            $post = Post::create([
                'body'          => $validatedData['body'],
                'file_id'       => $file->id,
                'latitude'      => $validatedData['latitude'],
                'longitude'     => $validatedData['longitude'],
                'visibility_id' => $validatedData['visibility'],
                'author_id'     => auth()->user()->id,
            ]);
            Log::debug("DB storage OK");
            return response()->json([
                'success' => true,
                'data'    => new PostResource($post)
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
        $post = Post::find($id);
        
        if ($post) {
            $this->authorize('view', $post);
            $post->loadCount(['likes','comments']);
            return response()->json([
                'success' => true,
                'data'    => new PostResource($post)
            ], 200);
        } else {
            return response()->json([
                'success'  => false,
                'message' => 'Post not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PostUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_workaround(PostUpdateRequest $request, $id)
    {
        // File upload workaround
        return $this->update($request, $id);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  PostUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdateRequest $request, $id)
    {
        $post = Post::find($id);

        if (empty($post)) {
            return response()->json([
                'success'  => false,
                'message' => 'Post not found'
            ], 404);
        }
        
        $this->authorize('update', $post);
        $validatedData = $request->validated();
        $upload        = $request->file('upload');
        
        if (is_null($upload) || $post->file->diskSave($upload)) {
            Log::debug("Updating DB...");
            if (!empty($validatedData['body'])) {
                $post->body = $validatedData['body'];
            }
            if (!empty($validatedData['latitude'])) {
                $post->latitude = $validatedData['latitude'];
            }
            if (!empty($validatedData['longitude'])) {
                $post->longitude = $validatedData['longitude'];
            }
            if (!empty($validatedData['visibility'])) {
                $post->visibility_id = $validatedData['visibility'];
            }
            $post->save();
            Log::debug("DB storage OK");
            return response()->json([
                'success' => true,
                'data'    => new PostResource($post)
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
        $post = Post::find($id);

        if (empty($post)) {
            return response()->json([
                'success'  => false,
                'message' => 'Post not found'
            ], 404);
        }

        $this->authorize('delete', $post);
        $post->delete();
        $post->file->diskDelete();
        
        return response()->json([
            'success' => true,
            'data'    => new PostResource($post)
        ], 200);
    }


    /**
     * Add like
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function like($id) 
    {
        try {
            $like = Like::create([
                'user_id'  => auth()->user()->id,
                'post_id' => $id
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Like already exists"
            ], 400);
        }
        
        return response()->json([
            'success' => true,
            'data'    => $like
        ], 200);
    }

    /**
     * Undo like
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unlike($id)
    {
        $like = Like::where([
            ['user_id', '=', auth()->user()->id],
            ['post_id', '=', $id],
        ])->first();

        if ($like) {
            $like->delete();
            return response()->json([
                'success' => true,
                'data'    => $like
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Like not exists"
            ], 404); 
        }
    }
}
