<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Tracks interface-level bandwidth usage on RouterOS v6.
 */
class UsageTracker
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getInterfaceStats(string $name): array
    {
        return $this->client->comm('/interface/print', [
            '?name' => $name,
            '.proplist' => 'rx-byte,tx-byte',
        ]);
    }
}
