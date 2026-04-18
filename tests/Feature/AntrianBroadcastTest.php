<?php

namespace Tests\Feature;

use App\Events\AntrianUpdated;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AntrianBroadcastTest extends TestCase
{
    public function test_antrian_updated_event_implements_should_broadcast_now(): void
    {
        $event = new AntrianUpdated(jadwalId: 1, nomorAntrian: 3);

        $this->assertInstanceOf(ShouldBroadcastNow::class, $event);
    }

    public function test_antrian_updated_broadcasts_on_public_antrian_channel(): void
    {
        $event = new AntrianUpdated(jadwalId: 1, nomorAntrian: 3);

        $channels = $event->broadcastOn();

        $this->assertCount(1, $channels);
        $this->assertEquals('antrian', $channels[0]->name);
    }

    public function test_antrian_updated_broadcasts_as_correct_event_name(): void
    {
        $event = new AntrianUpdated(jadwalId: 1, nomorAntrian: 3);

        $this->assertEquals('antrian.updated', $event->broadcastAs());
    }

    public function test_antrian_updated_broadcast_payload_contains_correct_keys(): void
    {
        $event = new AntrianUpdated(jadwalId: 7, nomorAntrian: 5);

        $payload = $event->broadcastWith();

        $this->assertArrayHasKey('jadwalId', $payload);
        $this->assertArrayHasKey('nomorAntrian', $payload);
        $this->assertEquals(7, $payload['jadwalId']);
        $this->assertEquals(5, $payload['nomorAntrian']);
    }

    public function test_antrian_updated_event_is_dispatched_when_faked(): void
    {
        Event::fake();

        event(new AntrianUpdated(jadwalId: 2, nomorAntrian: 4));

        Event::assertDispatched(AntrianUpdated::class, function ($e) {
            return $e->jadwalId === 2 && $e->nomorAntrian === 4;
        });
    }
}
