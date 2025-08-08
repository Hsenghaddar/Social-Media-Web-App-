<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index(){
        $notifications=Auth::user()->allNotifications();
        return view("notifications",["notifications"=>$notifications]);
    }
    public function clear(){
        foreach(Auth::user()->allNotifications() as $notification){
            $notification->delete();
        }
        return back();
    }
}
