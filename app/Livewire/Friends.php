<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Friend;
use Illuminate\Support\Facades\Auth;
class Friends extends Component
{
    public User $user;
    //$listeners is the array where we declare what events we want to respond to
    protected $listeners = ['refreshComponent' => '$refresh'];//$refresh is a Livewire built-in action that re-renders the component.
    public function mount(User $user){
        $this->user=$user;
    }
    public function unFriend($id){
        Friend::where(["sender_id"=>Auth::id(),"reciever_id"=>$id])->delete();
        Friend::where(["sender_id"=>$id,"reciever_id"=>Auth::id()])->delete();
        return;
    }
    public function render()
    {
        return view('livewire.friends');
    }
}
