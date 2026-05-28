<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages logging rules and reads system logs on RouterOS v6.
 */
class SyslogManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getLoggingRules(): array
    {
        return $this->client->comm('/system/logging/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getLogs(): array
    {
        return $this->client->comm('/log/print');
    }
}
