<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6;

use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;
use Mivo\LaravelMikrotikRos6\Services\ArpManager;
use Mivo\LaravelMikrotikRos6\Services\BridgeManager;
use Mivo\LaravelMikrotikRos6\Services\DhcpManager;
use Mivo\LaravelMikrotikRos6\Services\DnsManager;
use Mivo\LaravelMikrotikRos6\Services\FirewallManager;
use Mivo\LaravelMikrotikRos6\Services\HotspotManager;
use Mivo\LaravelMikrotikRos6\Services\InterfaceManager;
use Mivo\LaravelMikrotikRos6\Services\IpAddressManager;
use Mivo\LaravelMikrotikRos6\Services\IpPoolManager;
use Mivo\LaravelMikrotikRos6\Services\NtpManager;
use Mivo\LaravelMikrotikRos6\Services\PppoeManager;
use Mivo\LaravelMikrotikRos6\Services\QueueManager;
use Mivo\LaravelMikrotikRos6\Services\RadiusManager;
use Mivo\LaravelMikrotikRos6\Services\RouteManager;
use Mivo\LaravelMikrotikRos6\Services\RouterUserManager;
use Mivo\LaravelMikrotikRos6\Services\ScriptManager;
use Mivo\LaravelMikrotikRos6\Services\SessionMonitor;
use Mivo\LaravelMikrotikRos6\Services\SyslogManager;
use Mivo\LaravelMikrotikRos6\Services\SystemManager;
use Mivo\LaravelMikrotikRos6\Services\UsageTracker;
use Mivo\LaravelMikrotikRos6\Services\VpnManager;
use Mivo\LaravelMikrotikRos6\Services\WirelessManager;
use Mivo\LaravelMikrotikRos6\Support\QueryBuilder;
use Mivo\MikrotikRos6\Client;

/**
 * Manages multiple Mikrotik RouterOS v6 API connections.
 *
 * Provides fluent access to 22 Service Managers and a QueryBuilder
 * for simplified RouterOS operations within Laravel.
 */
class MikrotikManager
{
    /**
     * The application instance.
     */
    protected Application $app;

    /**
     * The active connection instances.
     *
     * @var array<string, Client>
     */
    protected array $connections = [];

    /**
     * Create a new manager instance.
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    // =========================================================
    // Connection Management
    // =========================================================

    /**
     * Get a Mikrotik connection instance.
     *
     * @param  string|array|null  $name  Connection name (string) or dynamic configuration (array).
     */
    public function connection(string|array|null $name = null): Client
    {
        // If an array is passed, build a dynamic client immediately (Hybrid approach)
        if (is_array($name)) {
            return $this->makeClient($name);
        }

        $name = $name ?: $this->getDefaultConnection();

        if (! isset($this->connections[$name])) {
            $this->connections[$name] = $this->makeConnection($name);
        }

        return $this->connections[$name];
    }

    /**
     * Make the Mikrotik connection instance.
     */
    protected function makeConnection(string $name): Client
    {
        $config = $this->configuration($name);

        return $this->makeClient($config);
    }

    /**
     * Build a Client instance from an array of configuration.
     */
    protected function makeClient(array $config): Client
    {
        if (empty($config['host']) || empty($config['username'])) {
            throw new InvalidArgumentException('Mikrotik connection must specify a host and username.');
        }

        $client = new Client;
        $client->timeout = (int) ($config['timeout'] ?? 3);
        $client->attempts = (int) ($config['attempts'] ?? 3);
        $client->delay = (int) ($config['delay'] ?? 1);
        $client->ssl = (bool) ($config['ssl'] ?? false);
        $client->debug = (bool) ($config['debug'] ?? false);

        $client->connect(
            $config['host'],
            $config['username'],
            $config['password'] ?? '',
            (int) ($config['port'] ?? ($client->ssl ? 8729 : 8728))
        );

        return $client;
    }

    /**
     * Get the configuration for a connection.
     */
    protected function configuration(string $name): array
    {
        $connections = $this->app['config']['mikrotik-ros6.connections'];

        if (is_null($config = $connections[$name] ?? null)) {
            throw new InvalidArgumentException("Mikrotik connection [{$name}] not configured.");
        }

        return $config;
    }

    /**
     * Get the default connection name.
     */
    public function getDefaultConnection(): string
    {
        return $this->app['config']['mikrotik-ros6.default'];
    }

    /**
     * Disconnect from the given connection and remove from cache.
     */
    public function purge(?string $name = null): void
    {
        $name = $name ?: $this->getDefaultConnection();

        if (isset($this->connections[$name])) {
            $this->connections[$name]->disconnect();
            unset($this->connections[$name]);
        }
    }

    // =========================================================
    // Fluent Query Builder
    // =========================================================

    /**
     * Create a fluent query builder for any RouterOS command endpoint.
     */
    public function query(string $endpoint): QueryBuilder
    {
        return new QueryBuilder($this->connection(), $endpoint);
    }

    // =========================================================
    // Service Managers (22 Total)
    // =========================================================

    public function arp(): ArpManager
    {
        return new ArpManager($this->connection());
    }

    public function bridge(): BridgeManager
    {
        return new BridgeManager($this->connection());
    }

    public function dhcp(): DhcpManager
    {
        return new DhcpManager($this->connection());
    }

    public function dns(): DnsManager
    {
        return new DnsManager($this->connection());
    }

    public function firewall(): FirewallManager
    {
        return new FirewallManager($this->connection());
    }

    public function hotspot(): HotspotManager
    {
        return new HotspotManager($this->connection());
    }

    public function interfaces(): InterfaceManager
    {
        return new InterfaceManager($this->connection());
    }

    public function ipAddress(): IpAddressManager
    {
        return new IpAddressManager($this->connection());
    }

    public function ipPool(): IpPoolManager
    {
        return new IpPoolManager($this->connection());
    }

    public function ntp(): NtpManager
    {
        return new NtpManager($this->connection());
    }

    public function pppoe(): PppoeManager
    {
        return new PppoeManager($this->connection());
    }

    public function queue(): QueueManager
    {
        return new QueueManager($this->connection());
    }

    public function radius(): RadiusManager
    {
        return new RadiusManager($this->connection());
    }

    public function routes(): RouteManager
    {
        return new RouteManager($this->connection());
    }

    public function routerUsers(): RouterUserManager
    {
        return new RouterUserManager($this->connection());
    }

    public function scripts(): ScriptManager
    {
        return new ScriptManager($this->connection());
    }

    public function sessionMonitor(): SessionMonitor
    {
        return new SessionMonitor($this->connection());
    }

    public function syslog(): SyslogManager
    {
        return new SyslogManager($this->connection());
    }

    public function system(): SystemManager
    {
        return new SystemManager($this->connection());
    }

    public function usageTracker(): UsageTracker
    {
        return new UsageTracker($this->connection());
    }

    public function vpn(): VpnManager
    {
        return new VpnManager($this->connection());
    }

    public function wireless(): WirelessManager
    {
        return new WirelessManager($this->connection());
    }

    // =========================================================
    // Magic Method Fallback
    // =========================================================

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->connection()->$method(...$parameters);
    }
}
