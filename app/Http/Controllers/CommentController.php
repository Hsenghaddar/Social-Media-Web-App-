<?php

namespace App\Http\Controllers;

use App\Events\UserNotified;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = $post->allComments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ]);
        if (Auth::id() != $post->user->id) {
            $message = Auth::user()->name . " commented on your post";
            $link = "/posts/" . $post->id;
            event(new UserNotified($post->user, $message, $link));
        }
        return back()->with('success', 'Comment added successfully!');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}
