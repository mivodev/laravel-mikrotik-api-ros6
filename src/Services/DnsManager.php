<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages DNS configuration and static entries on RouterOS v6.
 */
class DnsManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getConfig(): array
    {
        return $this->client->comm('/ip/dns/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getStatic(): array
    {
        return $this->client->comm('/ip/dns/static/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function addStatic(string $name, string $address): array
    {
        return $this->client->comm('/ip/dns/static/add', [
            'name' => $name,
            'address' => $address,
        ]);
    }
}
