<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages simple queues (bandwidth limiting) on RouterOS v6.
 */
class QueueManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getSimpleQueues(): array
    {
        return $this->client->comm('/queue/simple/print');
    }

    /**
     * @param  array<string, string>  $data
     * @return array<int, array<string, string>>
     */
    public function addSimpleQueue(array $data): array
    {
        return $this->client->comm('/queue/simple/add', $data);
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function removeSimpleQueue(string $name): array
    {
        $queue = $this->client->comm('/queue/simple/print', ['?name' => $name]);

        if (! empty($queue)) {
            return $this->client->comm('/queue/simple/remove', ['.id' => $queue[0]['.id']]);
        }

        return [];
    }
}
