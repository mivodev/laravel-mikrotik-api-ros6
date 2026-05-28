<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages hotspot users, hosts, and active sessions on RouterOS v6.
 */
class HotspotManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getActive(): array
    {
        return $this->client->comm('/ip/hotspot/active/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getHosts(): array
    {
        return $this->client->comm('/ip/hotspot/host/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getUsers(): array
    {
        return $this->client->comm('/ip/hotspot/user/print');
    }

    /**
     * @param  array<string, string>  $data
     * @return array<int, array<string, string>>
     */
    public function addUser(array $data): array
    {
        return $this->client->comm('/ip/hotspot/user/add', $data);
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function removeUser(string $name): array
    {
        $user = $this->client->comm('/ip/hotspot/user/print', ['?name' => $name]);

        if (! empty($user)) {
            return $this->client->comm('/ip/hotspot/user/remove', ['.id' => $user[0]['.id']]);
        }

        return [];
    }
}
