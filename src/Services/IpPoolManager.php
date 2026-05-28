<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages IP pools on RouterOS v6.
 *
 * Supports dual-stack (IPv4 pools + IPv6 prefix pools).
 */
class IpPoolManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getAll(): array
    {
        return $this->client->comm('/ip/pool/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getUsedAddresses(): array
    {
        return $this->client->comm('/ip/pool/used/print');
    }

    /**
     * Get IPv6 prefix pools for customer delegation.
     *
     * @return array<int, array<string, string>>
     */
    public function getV6Pools(): array
    {
        return $this->client->comm('/ipv6/pool/print');
    }
}
