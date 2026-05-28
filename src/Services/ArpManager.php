<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages ARP table entries on RouterOS v6.
 */
class ArpManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getAll(): array
    {
        return $this->client->comm('/ip/arp/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function add(string $address, string $macAddress, string $interface): array
    {
        return $this->client->comm('/ip/arp/add', [
            'address' => $address,
            'mac-address' => $macAddress,
            'interface' => $interface,
        ]);
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function remove(string $id): array
    {
        return $this->client->comm('/ip/arp/remove', ['.id' => $id]);
    }
}
