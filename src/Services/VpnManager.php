<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages VPN tunnels (SSTP/L2TP/PPTP) on RouterOS v6.
 */
class VpnManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getSstpSecrets(): array
    {
        return $this->client->comm('/interface/sstp-server/server/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getActiveTunnels(): array
    {
        return $this->client->comm('/ppp/active/print', ['?service' => 'sstp']);
    }
}
