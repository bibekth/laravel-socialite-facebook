<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Post::where('user_id', Auth::id())->get();
        return view('post', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('createpost');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $file = File::get($request->banner_image);
        $path = time() . $request->banner_image->getClientOriginalName();
        $savePath = 'posts/' . $path;
        Storage::disk('public')->put($savePath, $file);
        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'banner_image' => $savePath,
            'description' => $request->description,
        ]);
        if ($request->option) {
            foreach ($request->option as $option) {
                $options = Vote::create([
                    'post_id' => $post->id,
                    'option' => $option,
                ]);
            }
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        $post = Post::with(['comments','votes'])->where('slug', $request->slug)->first();
        return view('showpost',['post'=>$post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
