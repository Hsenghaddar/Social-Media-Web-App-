<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Request $request, Post $post)
    {
        $like = $post->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
            $liked=false;
        } else {
            $post->likes()->create(['user_id' => Auth::id()]);
            $liked=true;
        }

        return response()->json([
            "liked"=>$liked,
            "likes"=>$post->likes()->count()
        ]);;
    }
}
