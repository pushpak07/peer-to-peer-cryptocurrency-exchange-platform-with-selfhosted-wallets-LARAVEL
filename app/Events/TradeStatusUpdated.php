<?php

namespace App\Events;

use App\Models\Trade;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TradeStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User instance
     *
     * @var Trade
     */
    public $trade;

    /**
     * Create a new event instance.
     *
     * @param Trade $trade
     * @return void
     */
    public function __construct(Trade $trade)
    {
        $this->trade = $trade;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [
            new PrivateChannel("trade.{$this->trade->token}")
        ];

        if($this->trade->status == 'successful'){
            $channels[] = new PrivateChannel("administration");
        }

        return $channels;
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'confirmed' => $this->trade->confirmed,
            'dispute_comment' => $this->trade->dispute_comment,
            'dispute_by' => $this->trade->dispute_by,
            'status' => $this->trade->status,
        ];
    }
}
