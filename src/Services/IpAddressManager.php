<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages IP addresses on RouterOS v6.
 *
 * Supports dual-stack (IPv4 + IPv6 address assignment).
 */
class IpAddressManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getAll(): array
    {
        return $this->client->comm('/ip/address/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function add(string $address, string $interface): array
    {
        return $this->client->comm('/ip/address/add', [
            'address' => $address,
            'interface' => $interface,
        ]);
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getAllV6(): array
    {
        return $this->client->comm('/ipv6/address/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function addV6(string $address, string $interface): array
    {
        return $this->client->comm('/ipv6/address/add', [
            'address' => $address,
            'interface' => $interface,
        ]);
    }
}
