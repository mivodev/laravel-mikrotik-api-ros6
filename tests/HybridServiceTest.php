<?php

declare(strict_types=1);

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

// =========================================================
// Class Existence: Ensure all classes autoload correctly
// =========================================================

test('all 22 service manager classes exist', function () {
    expect(class_exists(ArpManager::class))->toBeTrue()
        ->and(class_exists(BridgeManager::class))->toBeTrue()
        ->and(class_exists(DhcpManager::class))->toBeTrue()
        ->and(class_exists(DnsManager::class))->toBeTrue()
        ->and(class_exists(FirewallManager::class))->toBeTrue()
        ->and(class_exists(HotspotManager::class))->toBeTrue()
        ->and(class_exists(InterfaceManager::class))->toBeTrue()
        ->and(class_exists(IpAddressManager::class))->toBeTrue()
        ->and(class_exists(IpPoolManager::class))->toBeTrue()
        ->and(class_exists(NtpManager::class))->toBeTrue()
        ->and(class_exists(PppoeManager::class))->toBeTrue()
        ->and(class_exists(QueueManager::class))->toBeTrue()
        ->and(class_exists(RadiusManager::class))->toBeTrue()
        ->and(class_exists(RouteManager::class))->toBeTrue()
        ->and(class_exists(RouterUserManager::class))->toBeTrue()
        ->and(class_exists(ScriptManager::class))->toBeTrue()
        ->and(class_exists(SessionMonitor::class))->toBeTrue()
        ->and(class_exists(SyslogManager::class))->toBeTrue()
        ->and(class_exists(SystemManager::class))->toBeTrue()
        ->and(class_exists(UsageTracker::class))->toBeTrue()
        ->and(class_exists(VpnManager::class))->toBeTrue()
        ->and(class_exists(WirelessManager::class))->toBeTrue();
});

test('query builder class exists', function () {
    expect(class_exists(QueryBuilder::class))->toBeTrue();
});

// =========================================================
// Manager: Method accessibility
// =========================================================

test('manager has all 22 service accessor methods and query', function () {
    $methods = [
        'arp', 'bridge', 'dhcp', 'dns', 'firewall', 'hotspot',
        'interfaces', 'ipAddress', 'ipPool', 'ntp', 'pppoe', 'queue',
        'radius', 'routes', 'routerUsers', 'scripts', 'sessionMonitor',
        'syslog', 'system', 'usageTracker', 'vpn', 'wireless', 'query',
    ];

    foreach ($methods as $method) {
        expect(method_exists(MikrotikManager::class, $method))
            ->toBeTrue("Method '{$method}' not found on MikrotikManager");
    }
});

// =========================================================
// Service Managers: Correct ROS6 Socket API (comm) usage
// =========================================================

test('service managers accept ros6 client via constructor', function () {
    $client = Mockery::mock(Client::class);

    $arp = new ArpManager($client);
    $hotspot = new HotspotManager($client);
    $system = new SystemManager($client);

    expect($arp)->toBeInstanceOf(ArpManager::class)
        ->and($hotspot)->toBeInstanceOf(HotspotManager::class)
        ->and($system)->toBeInstanceOf(SystemManager::class);
});

test('arp manager calls comm with /ip/arp/print', function () {
    $client = Mockery::mock(Client::class);
    $client->shouldReceive('comm')
        ->with('/ip/arp/print')
        ->once()
        ->andReturn([['address' => '192.168.1.1', 'mac-address' => 'AA:BB:CC:DD:EE:FF']]);

    $result = (new ArpManager($client))->getAll();

    expect($result)->toBeArray()
        ->and($result[0]['address'])->toBe('192.168.1.1');
});

test('hotspot manager calls comm for add/list operations', function () {
    $client = Mockery::mock(Client::class);
    $client->shouldReceive('comm')
        ->with('/ip/hotspot/user/print')
        ->once()
        ->andReturn([['name' => 'test-user']]);

    $client->shouldReceive('comm')
        ->with('/ip/hotspot/user/add', ['name' => 'new-user', 'password' => '123'])
        ->once()
        ->andReturn([]);

    $hotspot = new HotspotManager($client);

    expect($hotspot->getUsers())->toBeArray();
    expect($hotspot->addUser(['name' => 'new-user', 'password' => '123']))->toBeArray();
});

test('firewall manager supports ipv6 address-list via comm', function () {
    $client = Mockery::mock(Client::class);
    $client->shouldReceive('comm')
        ->with('/ipv6/firewall/address-list/add', [
            'list' => 'blocked',
            'address' => '2001:db8::1/128',
        ])
        ->once()
        ->andReturn([]);

    $result = (new FirewallManager($client))->addV6AddressList('blocked', '2001:db8::1/128');
    expect($result)->toBeArray();
});

test('query builder chains where/select and calls comm', function () {
    $client = Mockery::mock(Client::class);
    $client->shouldReceive('comm')
        ->with('/ip/hotspot/user/print', [
            '?profile' => 'Premium-1M',
            '.proplist' => 'name,limit-uptime',
        ])
        ->once()
        ->andReturn([['name' => 'dyzulk']]);

    $result = (new QueryBuilder($client, '/ip/hotspot/user/print'))
        ->where('profile', 'Premium-1M')
        ->select(['name', 'limit-uptime'])
        ->get();

    expect($result)->toBeArray()
        ->and($result[0]['name'])->toBe('dyzulk');
});

test('all service managers depend on ros6 client type', function () {
    $managers = [
        ArpManager::class, BridgeManager::class, DhcpManager::class,
        DnsManager::class, FirewallManager::class, HotspotManager::class,
    ];

    foreach ($managers as $managerClass) {
        $reflection = new ReflectionClass($managerClass);
        $param = $reflection->getConstructor()->getParameters()[0];
        expect($param->getType()->getName())->toBe(Client::class,
            "Expected {$managerClass} constructor to accept ".Client::class
        );
    }
});

test('manager throws on invalid connection name', function () {
    $app = app();
    $app['config']->set('mikrotik-ros6.connections', []);

    (new MikrotikManager($app))->connection('nonexistent');
})->throws(InvalidArgumentException::class);

test('manager throws on empty host config', function () {
    $app = app();
    (new MikrotikManager($app))->connection(['host' => '', 'username' => '']);
})->throws(InvalidArgumentException::class);
