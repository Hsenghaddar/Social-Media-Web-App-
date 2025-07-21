<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Friend extends Model
{
    protected $fillable=["sender_id","reciever_id","status"];
    public function sender(){
        return $this->belongsTo(User::class,"sender_id");//belongsTo is used when this model has the foreign key
    }
    public function reciever(){
        return $this->belongsTo(User::class,"reciever_id");//we use two to get the users of the reciever and the sender 
    }
    
}
