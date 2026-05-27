<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Facades;

use Illuminate\Support\Facades\Facade;
use Mivo\LaravelMikrotikRos6\MikrotikManager;
use Mivo\MikrotikRos6\Client;

/**
 * @method static \Mivo\MikrotikRos6\Client connection(string|array|null $name = null)
 * @method static void purge(?string $name = null)
 * @method static string getDefaultConnection()
 * @method static bool connect(string $host, string $username = 'admin', string $password = '', int $port = 8728)
 * @method static void disconnect()
 * @method static bool isConnected()
 * @method static array comm(string $command, array $params = [])
 *
 * @see MikrotikManager
 * @see Client
 */
class MikrotikRos6 extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'mikrotik.ros6';
    }
}
