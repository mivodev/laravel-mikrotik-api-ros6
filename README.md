![Laravel Mikrotik API ROS6](https://raw.githubusercontent.com/mivodev/.github/main/profile/assets/img/logo-banner.png)

# Laravel Mikrotik API ROS6

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-8892BF.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/laravel-%3E%3D10.0-FF2D20.svg)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

A clean, elegant Laravel wrapper for `mivodev/mikrotik-api-ros6`. Provides a ServiceProvider, Facade, and configuration file for seamless integration into your Laravel application.

## Installation

```bash
composer require mivodev/laravel-mikrotik-api-ros6
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=mikrotik-ros6-config
```

## Configuration

After publishing, you can configure your default router credentials in your `.env` file:

```env
MIKROTIK_ROS6_HOST=192.168.1.1
MIKROTIK_ROS6_USERNAME=admin
MIKROTIK_ROS6_PASSWORD=rahasia
MIKROTIK_ROS6_PORT=8728
MIKROTIK_ROS6_SSL=false
MIKROTIK_ROS6_TIMEOUT=3
MIKROTIK_ROS6_ATTEMPTS=5
```

## Usage

### 1. Dedicated Service Managers (Sugar Syntax)
Rather than executing raw CLI commands, you can use 22 dedicated service managers that cover all core ISP and network operations with clean, autocomplete-friendly PHP methods:

```php
use Mivo\LaravelMikrotikRos6\Facades\MikrotikRos6;

// 1. Hotspot Management
$users = MikrotikRos6::hotspot()->getUsers();
MikrotikRos6::hotspot()->addUser([
    'name' => 'dyzulk',
    'password' => 'secret123',
    'profile' => 'Premium-1M'
]);

// 2. PPPoE Secret Management
$activeSessions = MikrotikRos6::pppoe()->getActive();
MikrotikRos6::pppoe()->disconnect('customer_123'); // Disconnects session by print-lookup + remove

// 3. Simple Queues (Bandwidth Limiting)
MikrotikRos6::queue()->addSimpleQueue([
    'name' => 'customer_123_limit',
    'target' => '192.168.88.10',
    'max-limit' => '1M/2M'
]);

// 4. Dual-Stack IPv4 / IPv6 Support
MikrotikRos6::ipAddress()->addV6('2001:db8::1/64', 'ether1'); // IPv6 Address assignment
MikrotikRos6::firewall()->addV6AddressList('blocked', '2001:db8::2'); // IPv6 address list isolation
```

Available managers: `arp()`, `bridge()`, `dhcp()`, `dns()`, `firewall()`, `hotspot()`, `interfaces()`, `ipAddress()`, `ipPool()`, `ntp()`, `pppoe()`, `queue()`, `radius()`, `routes()`, `routerUsers()`, `scripts()`, `sessionMonitor()`, `syslog()`, `system()`, `usageTracker()`, `vpn()`, `wireless()`.

### 2. Fluent Query Builder
Execute targeted query commands easily using fluent builder syntax:

```php
$users = MikrotikRos6::query('/ip/hotspot/user/print')
    ->where('profile', 'Premium-1M')
    ->whereRegex('name', '^dyzulk')
    ->select(['name', 'limit-uptime'])
    ->get();
```

### 3. Hybrid Multi-Tenant Connections
Perfect for SaaS applications (like Mivo Enterprise) where router credentials are retrieved dynamically from the database:

```php
use App\Models\Router;
use Mivo\LaravelMikrotikRos6\Facades\MikrotikRos6;

$router = Router::find(1);

// Establish dynamic connection from database model
$client = MikrotikRos6::connection([
    'host'     => $router->vpn_assigned_ip,
    'username' => $router->api_username,
    'password' => $router->api_password,
    'port'     => 8728,
]);

// Use any service manager on this specific router
$users = $client->hotspot()->getUsers();
```

---

## 4. Artisan Diagnosis Command
Diagnose and ping router connections easily using the Artisan tool:

```bash
# Ping using a database Router ID
php artisan mivo:ros6-ping 1
```

## License

MIT License. See [LICENSE](LICENSE) for details.