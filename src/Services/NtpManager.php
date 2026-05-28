<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages NTP client and system clock on RouterOS v6.
 */
class NtpManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getClientConfig(): array
    {
        return $this->client->comm('/system/ntp/client/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getClock(): array
    {
        return $this->client->comm('/system/clock/print');
    }
}
