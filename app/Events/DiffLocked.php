<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\DiffLock;

class DiffLocked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $diffLock;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DiffLock $lock)
    {
        //
        $this->lock;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $diffId = $this->lock->diff()->first();
        return new PrivateChannel('diffs.locked.' . $diffId);
    }
}
