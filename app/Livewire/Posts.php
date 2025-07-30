<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Posts extends Component
{
    protected $listeners = ['refreshComponent' => '$refresh'];
    public User $user;
    public function mount($user)
    {
        $this->$user = $user;
    }
    public function render()
    {
        if ($this->user->id == Auth::id() || $this->user->friends->contains(Auth::user())) {
            $posts = $this->user->posts;
        } else {
            $posts = $this->user->posts->where("privacy", 0);
        }
        return view('livewire.posts',["user"=>$this->user,"posts"=>$posts]);
    }
}
