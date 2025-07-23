<?php

namespace App\Livewire;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LikePosts extends Component
{
    public $post;//i receive the post i sent in view here
    public function toggle()
    {
        $like = $this->post->likes()->where('user_id', Auth::id())->first();
        if ($like) {
            $like->delete();
        } else {
            $this->post->likes()->create(['user_id' => Auth::id()]);
        }
    }
    public function render()
    {
        return view('livewire.like-posts');
    }
}
