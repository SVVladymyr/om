<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Client;
class NewSpecificationAppointedToClient
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $client;

    public $new_specification_id;

    public $old_specification_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Client $client, $new_specification_id, $old_specification_id)
    {
        $this->client = $client;

        $this->new_specification_id = $new_specification_id;

        $this->old_specification_id = $old_specification_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
