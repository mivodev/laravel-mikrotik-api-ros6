<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Monitors active PPPoE and Hotspot session counts on RouterOS v6.
 */
class SessionMonitor
{
    public function __construct(protected Client $client) {}

    public function getPppActiveCount(): int
    {
        return count($this->client->comm('/ppp/active/print'));
    }

    public function getHotspotActiveCount(): int
    {
        return count($this->client->comm('/ip/hotspot/active/print'));
    }
}
