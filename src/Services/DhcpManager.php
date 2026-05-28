<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages DHCP server leases on RouterOS v6.
 *
 * Supports dual-stack (IPv4 leases + IPv6 DHCPv6 bindings).
 */
class DhcpManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getLeases(): array
    {
        return $this->client->comm('/ip/dhcp-server/lease/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function makeStatic(string $macAddress, string $ipAddress): array
    {
        return $this->client->comm('/ip/dhcp-server/lease/add', [
            'mac-address' => $macAddress,
            'address' => $ipAddress,
        ]);
    }

    /**
     * Get DHCPv6 server bindings for IPv6 address allocation.
     *
     * @return array<int, array<string, string>>
     */
    public function getV6Bindings(): array
    {
        return $this->client->comm('/ipv6/dhcp-server/binding/print');
    }
}
