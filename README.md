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

Use the `MikrotikRos6` facade to easily communicate with your routers anywhere in your Laravel app.

### Using Default Connection

By default, the Facade uses the connection defined in `.env`.

```php
use Mivo\LaravelMikrotikRos6\Facades\MikrotikRos6;

// Execute commands on the default router
$users = MikrotikRos6::comm('/ip/hotspot/user/print');
```

### Hybrid Multi-Tenant Connections

For SaaS platforms like **Mivo Enterprise**, you don't store router credentials in `.env`. Instead, you retrieve them dynamically from your database. The Manager supports passing an array directly to establish a dynamic, cached connection:

```php
use App\Models\Router;
use Mivo\LaravelMikrotikRos6\Facades\MikrotikRos6;

$router = Router::find(1);

// Build connection dynamically from database model
$client = MikrotikRos6::connection([
    'host'     => $router->vpn_assigned_ip,
    'username' => $router->api_username,
    'password' => $router->api_password,
    'port'     => 8728,
]);

// Execute command on that specific router
$activeUsers = $client->comm('/ip/hotspot/active/print');

// Disconnect from the specific router
$client->disconnect();
```

### Advanced Examples

See the [core package documentation](https://github.com/mivodev/mikrotik-api-ros6) for full usage of the `comm()` method, including filtering, regex, and error handling.

## License

MIT License. See [LICENSE](LICENSE) for details.