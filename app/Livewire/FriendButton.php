<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Friend;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportEvents\HandlesEvents;

class FriendButton extends Component
{
    use HandlesEvents;

    public User $user;
    public function mount(User $user)
    { //constructor to initialize the props coming from the parent
        if (!$user ||!User::find($user->id)) { //second layer of protection =>check whether the user exits or no to prevent sending a request to a user not found
            abort(404, 'User not found.');
        }
        $this->user = $user; //we are saying let the property user here be equal to the model instace coming from the prop
    }

    public function addFriend()
    {
        if (Auth::id() === $this->user->id) {//second layer of protection
            abort(403, 'You cannot send a friend request to yourself.');
            return;
        }
        $request = Friend::where(["sender_id" => $this->user->id, "reciever_id" => Auth::id()])->first();
        if ($request) {
            $request->status = "accepted";
            $request->save();
            $this->dispatch('refreshComponent');
        } else if (!Friend::where('sender_id', Auth::id())->where('reciever_id', $this->user->id)->exists()) { //check whether this request has been sent since user can call this through console as much as he want so a duplicate error could occur in database
            Friend::create(["sender_id" => Auth::id(), "reciever_id" => $this->user->id]);
        }
    }
    public function cancel()
    {
        Friend::where(["sender_id" => Auth::id(), "reciever_id" => $this->user->id, "status" => "pending"])->delete();
    }
    public function unFriend()
    {
        Friend::where(["sender_id" => Auth::id(), "reciever_id" => $this->user->id])->delete();
        Friend::where(["sender_id" => $this->user->id, "reciever_id" => Auth::id()])->delete();
        $this->dispatch('refreshComponent');
    }
    public function accept()
    {
        $friendRequest = Friend::where(["sender_id" => $this->user->id, "reciever_id" => Auth::id(), "status" => "pending"])->first();
        if ($friendRequest) { //check whether there is a friendRequest since the user can call this function in console before a request has been sent
            $friendRequest->status = "accepted";
            $friendRequest->save();
            $this->dispatch('refreshComponent'); //This line dispatches a Livewire event named 'refreshComponent', It's how you trigger communication between components
        }
    }
    public function decline()
    {
        Friend::where(["sender_id" => $this->user->id, "reciever_id" => Auth::id(), "status" => "pending"])->delete();
    }
    public function render()
    {
        $isRequestSent = Friend::where('sender_id', Auth::id())->where('reciever_id', $this->user->id)->where("status", "pending")->first();
        $isRequestRecieved = Friend::where('sender_id', $this->user->id)->where('reciever_id', Auth::id())->where("status", "pending")->first();
        return view('livewire.friend-button', ["isRequestSent" => $isRequestSent, "isRequestRecieved" => $isRequestRecieved]);
    }
}
