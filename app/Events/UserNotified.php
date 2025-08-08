<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
class UserNotified implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;
    public $link;

    public function __construct($user,$message,$link)
    {
        $this->user=$user;
        $this->message=$message;
        $this->link=$link;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    //when we call event(...), Laravel will Automatically call these functions
    public function broadcastOn(): array//This method tells Laravel where (on which channel) to broadcast the event.
    {
        return [
            new PrivateChannel('notifications.'.$this->user->id)//PrivateChannel ensures only authenticated users who are authorized can listen to this channel.
        ];
    }
    // public function broadcastWith(){//This method defines the data that will be sent with the event.
    //     return [
    //         "message"=>$this->message,
    //         "link"=>$this->link
    //     ];
    // }
}
