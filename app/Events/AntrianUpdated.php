<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AntrianUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $jadwalId;
    public int $nomorAntrian;

    public function __construct(int $jadwalId, int $nomorAntrian)
    {
        $this->jadwalId = $jadwalId;
        $this->nomorAntrian = $nomorAntrian;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('antrian'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'antrian.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'jadwalId'      => $this->jadwalId,
            'nomorAntrian'  => $this->nomorAntrian,
        ];
    }
}
