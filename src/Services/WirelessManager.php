<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages wireless interfaces and security profiles on RouterOS v6.
 */
class WirelessManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getInterfaces(): array
    {
        return $this->client->comm('/interface/wireless/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getSecurityProfiles(): array
    {
        return $this->client->comm('/interface/wireless/security-profiles/print');
    }
}
