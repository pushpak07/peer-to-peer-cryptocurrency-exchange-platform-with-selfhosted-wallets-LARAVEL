<?php

namespace App\Events;

use App\Models\TradeChat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewMessageAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var TradeChat
     */
    public $chat;

    /**
     * Create a new event instance.
     *
     * @param $chat
     * @return void
     */
    public function __construct($chat)
    {
        $this->chat = $chat;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $chat = $this->chat;

        $channels = [];

        if ($chat->user_id != $chat->trade->partner_id) {
            $channels[] = new PrivateChannel("user.{$chat->trade->partner_id}");
        }

        if ($chat->user_id != $chat->trade->user_id) {
            $channels[] = new PrivateChannel("user.{$chat->trade->user_id}");
        }


        return $channels;
    }
}
