<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages system identity, resources, and power control on RouterOS v6.
 */
class SystemManager
{
    public function __construct(protected Client $client) {}

    public function getIdentity(): string
    {
        $res = $this->client->comm('/system/identity/print');

        return $res[0]['name'] ?? 'Unknown';
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getResources(): array
    {
        return $this->client->comm('/system/resource/print');
    }

    public function reboot(): void
    {
        $this->client->comm('/system/reboot');
    }
}
