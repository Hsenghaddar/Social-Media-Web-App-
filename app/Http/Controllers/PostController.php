<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            //get all public posts and all posts where the user of the post is my friend
            $posts=Post::where("privacy",0)->orWhereIn("user_id",Auth::user()->friends->pluck("id"))->latest()->paginate(10);
        } else {
            $posts = Post::where("privacy", 0)->latest()->paginate(10);
        }
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            "media"=>["nullable",File::types(["jpg","jpeg","gif","webp","png","mp4","mov","ogg","webm"])->max(10240)]//define the file types we accept and the maximum size can be uploaded 
        ]);
        $media_path=null;
        $media_mime=null;
        $media_type="none";
            if(isset($validated['media']) && $validated['media']){//cant use request->has("media") since even if we didnt select a file the key will still be sent to the request
                $media_path=Auth::id() ."_" .time() . "." .$validated["media"]->extension();//generate a random name for the image so that two images in the database cant have the same name\
                $media_mime=$validated["media"]->getMimeType();//image/png ...
                $media_type=str_starts_with($media_mime,"image/") ? "image" : "video";
                $validated["media"]->storeAs("/assets/uploads/",$media_path,"local");//store the file in the private disc(named as local) in storage under the folders assets/uploads
                //store automatically save files in storage/app and we can decide which disc by putting local
            
            }
        Post::create([
            "user_id"=>Auth::id(),
            "content"=>$validated["content"],
            "privacy"=>$request->has("privacy"), //if checkbox is checked privacy will be sent with the request else privacy will not be sent with the request
            "media_path"=>$media_path,
            "media_mime"=>$media_mime,
            "media_type"=>$media_type
        ]);
        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        $post->update([
            'content' => $request->content,
            "privacy" => $request->has("privacy")
        ]);
        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }

    public function profile(string $id)
    {
        $user = User::findOrFail($id);
        return view("posts.profile", ["user" => $user]);
    }
    public function friendsPosts()
    {
        $friends = Auth::user()->friends;
        return view("posts.friends", ["friends" => $friends]);
    }
    function getMedia(Post $post){
        $media=$post->media_path;
        return response()->file(Storage::disk("local")->path("/assets/uploads/".$media));
    }
}
