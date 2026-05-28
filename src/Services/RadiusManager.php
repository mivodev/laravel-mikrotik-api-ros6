<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages RADIUS client configuration on RouterOS v6.
 */
class RadiusManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getClients(): array
    {
        return $this->client->comm('/radius/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function addClient(string $address, string $secret, string $service = 'ppp'): array
    {
        return $this->client->comm('/radius/add', [
            'address' => $address,
            'secret' => $secret,
            'service' => $service,
        ]);
    }
}
