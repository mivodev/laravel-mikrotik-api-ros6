<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Facades;

use Illuminate\Support\Facades\Facade;
use Mivo\LaravelMikrotikRos6\MikrotikManager;
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
 * @method static Client connection(string|array|null $name = null)
 * @method static void purge(?string $name = null)
 * @method static string getDefaultConnection()
 * @method static QueryBuilder query(string $endpoint)
 * @method static ArpManager arp()
 * @method static BridgeManager bridge()
 * @method static DhcpManager dhcp()
 * @method static DnsManager dns()
 * @method static FirewallManager firewall()
 * @method static HotspotManager hotspot()
 * @method static InterfaceManager interfaces()
 * @method static IpAddressManager ipAddress()
 * @method static IpPoolManager ipPool()
 * @method static NtpManager ntp()
 * @method static PppoeManager pppoe()
 * @method static QueueManager queue()
 * @method static RadiusManager radius()
 * @method static RouteManager routes()
 * @method static RouterUserManager routerUsers()
 * @method static ScriptManager scripts()
 * @method static SessionMonitor sessionMonitor()
 * @method static SyslogManager syslog()
 * @method static SystemManager system()
 * @method static UsageTracker usageTracker()
 * @method static VpnManager vpn()
 * @method static WirelessManager wireless()
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
