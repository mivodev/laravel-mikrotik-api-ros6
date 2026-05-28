<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages PPPoE secrets and active sessions on RouterOS v6.
 */
class PppoeManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getActive(): array
    {
        return $this->client->comm('/ppp/active/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getSecrets(): array
    {
        return $this->client->comm('/ppp/secret/print');
    }

    /**
     * @param  array<string, string>  $data
     * @return array<int, array<string, string>>
     */
    public function addSecret(array $data): array
    {
        return $this->client->comm('/ppp/secret/add', $data);
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function removeSecret(string $name): array
    {
        $secret = $this->client->comm('/ppp/secret/print', ['?name' => $name]);

        if (! empty($secret)) {
            return $this->client->comm('/ppp/secret/remove', ['.id' => $secret[0]['.id']]);
        }

        return [];
    }

    /**
     * Force disconnect an active PPPoE session by username.
     *
     * @return array<int, array<string, string>>
     */
    public function disconnect(string $name): array
    {
        $active = $this->client->comm('/ppp/active/print', ['?name' => $name]);

        if (! empty($active)) {
            return $this->client->comm('/ppp/active/remove', ['.id' => $active[0]['.id']]);
        }

        return [];
    }
}
