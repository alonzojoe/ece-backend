<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorStored implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sensorData;

    public function __construct($sensorData)
    {
        $this->sensorData = $sensorData;
    }

    public function broadcastOn()
    {
        return new Channel('sensor-data'); // Public channel
    }

    public function broadcastAs()
    {
        return 'sensor.stored'; // Event name
    }
}
