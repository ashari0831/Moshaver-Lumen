<?php

namespace App\Events;
use Illuminate\Broadcasting\Channel;
// use Illuminate\Broadcasting\InteractWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
// use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageSent extends Event implements ShouldBroadcast
{
    

    public $chatMessage;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->chatMessage = $message;
    }


    public function broadcastOn(){
        return new PrivateChannel('chat.' . $this->chatMessage->chat_id);
    }
}
