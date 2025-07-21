<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    public function sendFriendRequest($id){
        $request=Friend::where(["sender_id"=>$id,"reciever_id"=>Auth::id()])->first();
        if($request){
            $request->status="accepted";
            $request->save();
            $user=User::find($id);
            $message="You and ".$user->name." are now friends";
        }else{
            Friend::create(["sender_id"=>Auth::id(),"reciever_id"=>$id]);
            $message="Friend request sent successfully";
        }
        return back()->with("success",$message);
    }
    public function removeFriendRequest($id){
        Friend::where(["sender_id"=>Auth::id(),"reciever_id"=>$id])->delete();
        Friend::where(["sender_id"=>$id,"reciever_id"=>Auth::id()])->delete();
        $message="Friend request removed successfully";
        return back()->with("success",$message);
    }
    public function acceptFriendRequest($id){
        $friendRequest=Friend::where(["sender_id"=>$id,"reciever_id"=>Auth::id()])->first();
        $friendRequest->status="accepted";
        $friendRequest->save();
        $message="Request accepted successfully";
        return back()->with("success",$message);
    }
    public function declineFriendRequest($id){
        $friendRequest=Friend::where(["sender_id"=>$id,"reciever_id"=>Auth::id()])->first();
        $friendRequest->status="rejected";
        $friendRequest->save();
        $message="Request rejected successfully";
        return back()->with("success",$message);
    }
}
