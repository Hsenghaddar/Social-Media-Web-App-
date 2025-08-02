<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Friend;
use Illuminate\Support\Facades\Auth;

class FriendRequests extends Component
{
    public function accept($id)
    {
        $friendRequest = Friend::where(["sender_id" => $id, "reciever_id" => Auth::id(), "status" => "pending"])->first();
        if ($friendRequest) {
            $friendRequest->status = "accepted";
            $friendRequest->save();
            $this->dispatch('refreshComponent');
        }
    }
    public function decline($id)
    {
        Friend::where(["sender_id" => $id, "reciever_id" => Auth::id(), "status" => "pending"])->delete();
    }
    public function render()
    {
        $receivedRequests = Friend::where("reciever_id", Auth::id())->where("status", "pending")->get();
        $sentRequests = Friend::where("sender_id", Auth::id())->where("status", "pending")->get();
        return view('livewire.friend-requests', ["receivedRequests" => $receivedRequests, "sentRequests" => $sentRequests]);
    }
}
