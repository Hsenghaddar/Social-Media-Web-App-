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
    public function mount(User $user){//constructor to initialize the props coming from the parent
        $this->user=$user;//we are saying let the property user here be equal to the model instace coming from the prop
    }

    public function addFriend($id){
        $request=Friend::where(["sender_id"=>$id,"reciever_id"=>Auth::id()])->first();
        if($request){
            $request->status="accepted";
            $request->save();
            $this->dispatch('refreshComponent');
            return;
        }else{
            Friend::create(["sender_id"=>Auth::id(),"reciever_id"=>$id]);
        }
        return;
    }
    public function cancel(){
        Friend::where(["sender_id"=>Auth::id(),"reciever_id"=>$this->user->id,"status"=>"pending"])->delete();
        return;
    }
    public function unFriend($id){
        Friend::where(["sender_id"=>Auth::id(),"reciever_id"=>$id])->delete();
        Friend::where(["sender_id"=>$id,"reciever_id"=>Auth::id()])->delete();
        $this->dispatch('refreshComponent');
        return;
    }
    public function accept(){
        $friendRequest=Friend::where(["sender_id"=>$this->user->id,"reciever_id"=>Auth::id(),"status"=>"pending"])->first();
        $friendRequest->status="accepted";
        $friendRequest->save();
        $this->dispatch('refreshComponent');//This line dispatches a Livewire event named 'refreshComponent', It's how you trigger communication between components
        return;
    }
    public function decline(){
        Friend::where(["sender_id"=>$this->user->id,"reciever_id"=>Auth::id(),"status"=>"pending"])->delete();
    }
    public function render()
    {
        $isRequestSent = Friend::where('sender_id', Auth::id())->where('reciever_id', $this->user->id)->where("status","pending")->first();
        $isRequestRecieved=Friend::where('sender_id',$this->user->id)->where('reciever_id', Auth::id())->where("status","pending")->first();
        return view('livewire.friend-button',["isRequestSent"=>$isRequestSent,"isRequestRecieved"=>$isRequestRecieved]);
    }
}
