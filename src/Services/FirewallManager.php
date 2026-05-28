<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages firewall rules and address-lists on RouterOS v6.
 *
 * Supports dual-stack (IPv4 + IPv6 address-lists) for customer isolation.
 */
class FirewallManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getNatRules(): array
    {
        return $this->client->comm('/ip/firewall/nat/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function addAddressList(string $list, string $address): array
    {
        return $this->client->comm('/ip/firewall/address-list/add', [
            'list' => $list,
            'address' => $address,
        ]);
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function removeAddressList(string $list, string $address): array
    {
        $item = $this->client->comm('/ip/firewall/address-list/print', [
            '?list' => $list,
            '?address' => $address,
        ]);

        if (! empty($item)) {
            return $this->client->comm('/ip/firewall/address-list/remove', ['.id' => $item[0]['.id']]);
        }

        return [];
    }

    /**
     * Add an IPv6 address to a firewall address-list.
     *
     * @return array<int, array<string, string>>
     */
    public function addV6AddressList(string $list, string $address): array
    {
        return $this->client->comm('/ipv6/firewall/address-list/add', [
            'list' => $list,
            'address' => $address,
        ]);
    }

    /**
     * Remove an IPv6 address from a firewall address-list.
     *
     * @return array<int, array<string, string>>
     */
    public function removeV6AddressList(string $list, string $address): array
    {
        $item = $this->client->comm('/ipv6/firewall/address-list/print', [
            '?list' => $list,
            '?address' => $address,
        ]);

        if (! empty($item)) {
            return $this->client->comm('/ipv6/firewall/address-list/remove', ['.id' => $item[0]['.id']]);
        }

        return [];
    }
}
