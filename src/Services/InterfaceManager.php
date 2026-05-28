<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Monitors network interfaces and traffic on RouterOS v6.
 */
class InterfaceManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getAll(): array
    {
        return $this->client->comm('/interface/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getTraffic(string $interface): array
    {
        return $this->client->comm('/interface/monitor-traffic', [
            'interface' => $interface,
            'once' => '',
        ]);
    }
}
