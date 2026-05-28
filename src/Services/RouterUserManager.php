<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages RouterOS system users and groups on RouterOS v6.
 */
class RouterUserManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getUsers(): array
    {
        return $this->client->comm('/user/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getGroups(): array
    {
        return $this->client->comm('/user/group/print');
    }
}
