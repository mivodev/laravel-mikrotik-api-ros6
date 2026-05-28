<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages IP routes on RouterOS v6.
 *
 * Supports dual-stack (IPv4 + IPv6 routing).
 */
class RouteManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getRoutes(): array
    {
        return $this->client->comm('/ip/route/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function addRoute(string $dstAddress, string $gateway): array
    {
        return $this->client->comm('/ip/route/add', [
            'dst-address' => $dstAddress,
            'gateway' => $gateway,
        ]);
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getV6Routes(): array
    {
        return $this->client->comm('/ipv6/route/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function addV6Route(string $dstAddress, string $gateway): array
    {
        return $this->client->comm('/ipv6/route/add', [
            'dst-address' => $dstAddress,
            'gateway' => $gateway,
        ]);
    }
}
