<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages bridge interfaces and ports on RouterOS v6.
 */
class BridgeManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getBridges(): array
    {
        return $this->client->comm('/interface/bridge/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getPorts(): array
    {
        return $this->client->comm('/interface/bridge/port/print');
    }
}
