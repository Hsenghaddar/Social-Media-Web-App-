<?php

namespace App\Livewire;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\User;
class Notifications extends Component
{
    protected $listeners = ['newNotification' => 'notify'];
    public $showDropDown=false;
    public function notify($detail){
        $this->showDropDown=$detail;
    }
    public function readAll(){
        $notifications=Notification::where(["user_id"=>Auth::id(),"is_read"=>false])->latest()->get();
        foreach($notifications as $notification){
            $notification->is_read=true;
            $notification->save();
        }
    }
    public function render()
    {
        $notifications=Notification::where(["user_id"=>Auth::id(),"is_read"=>false])->latest()->get();
        return view('livewire.notifications', ["notifications" => $notifications]);
    }
}
