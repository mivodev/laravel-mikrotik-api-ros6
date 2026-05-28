<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Console\Commands;

use App\Models\Router;
use Illuminate\Console\Command;
use Mivo\LaravelMikrotikRos6\Facades\MikrotikRos6;

/**
 * Artisan command to ping and diagnose a Mikrotik RouterOS v6 connection.
 *
 * Supports DB ID lookup, option flags, or step-by-step interactive mode.
 */
class RouterPingCommand extends Command
{
    protected $signature = 'mivo:ros6-ping 
                            {router_id? : The database ID of the router to ping} 
                            {--host= : Manual Router IP or Hostname/Domain} 
                            {--username= : Manual API username} 
                            {--password= : Manual API password} 
                            {--port=8728 : Manual API port}';

    protected $description = 'Ping and check Mikrotik RouterOS v6 API (Socket) connection. Supports DB ID lookup, option flags, or step-by-step interactive mode.';

    public function handle(): int
    {
        $host = $this->option('host');
        $username = $this->option('username');
        $password = $this->option('password');
        $port = (int) $this->option('port');

        $routerId = $this->argument('router_id');

        if ($routerId !== null) {
            /** @var Router|null $router */
            $router = Router::find($routerId);

            if (! $router) {
                $this->error('Router not found in database!');

                return self::FAILURE;
            }

            $host = $router->vpn_assigned_ip;
            $username = $router->api_username;
            $password = $router->api_password;
            $port = $router->api_port ? (int) $router->api_port : 8728;

            $this->info("Connecting using database Router '{$router->name}' ({$host})...");
        } elseif (empty($host)) {
            // Interactive Mode
            $this->info('No router_id or --host specified. Starting step-by-step interactive mode:');

            $host = $this->ask('1. Router Host/IP address');
            if (empty($host)) {
                $this->error('Host cannot be empty.');

                return self::FAILURE;
            }

            $username = $this->ask('2. API Username', 'admin');
            $password = $this->secret('3. API Password') ?? '';
            $port = (int) $this->ask('4. API Port', '8728');
        } else {
            // Host is provided via --host flag
            $username = $username ?: 'admin';
            $password = $password ?: '';
            $this->info("Connecting to manual host {$host}:{$port}...");
        }

        try {
            $client = MikrotikRos6::connection([
                'host' => $host,
                'username' => $username,
                'password' => $password,
                'port' => $port,
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
