<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Console\Commands;

use Illuminate\Console\Command;
use Mivo\LaravelMikrotikRos6\Facades\MikrotikRos6;

/**
 * Artisan command to ping and diagnose a Mikrotik RouterOS v6 connection.
 *
 * Fetches router credentials dynamically from the database.
 */
class RouterPingCommand extends Command
{
    protected $signature = 'mivo:ros6-ping {router_id}';

    protected $description = 'Ping and check Mikrotik RouterOS v6 connection';

    public function handle(): int
    {
        /** @var \App\Models\Router|null $router */
        $router = \App\Models\Router::find($this->argument('router_id'));

        if (! $router) {
            $this->error('Router not found!');

            return self::FAILURE;
        }

        $this->info("Connecting to router '{$router->name}' ({$router->vpn_assigned_ip})...");

        try {
            $client = MikrotikRos6::connection([
                'host' => $router->vpn_assigned_ip,
                'username' => $router->api_username,
                'password' => $router->api_password,
            ]);

            $identity = $client->comm('/system/identity/print');
            $this->info('Successfully connected to RouterOS!');
            $this->comment('Identity: '.($identity[0]['name'] ?? 'Unknown'));

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to connect: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
