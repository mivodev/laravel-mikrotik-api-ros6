<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Services;

use Mivo\MikrotikRos6\Client;

/**
 * Manages scripts and scheduler on RouterOS v6.
 */
class ScriptManager
{
    public function __construct(protected Client $client) {}

    /**
     * @return array<int, array<string, string>>
     */
    public function getScripts(): array
    {
        return $this->client->comm('/system/script/print');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function runScript(string $name): array
    {
        return $this->client->comm('/system/script/run', ['number' => $name]);
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getScheduler(): array
    {
        return $this->client->comm('/system/scheduler/print');
    }
}
