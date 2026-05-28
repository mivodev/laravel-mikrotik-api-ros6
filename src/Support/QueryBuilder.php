<?php

declare(strict_types=1);

namespace Mivo\LaravelMikrotikRos6\Support;

use Mivo\MikrotikRos6\Client;

/**
 * Fluent query builder for RouterOS API commands.
 *
 * Translates Eloquent-style chaining into raw Mikrotik API query parameters.
 *
 * Usage:
 *   $builder = new QueryBuilder($client, '/ip/hotspot/user/print');
 *   $result = $builder->where('profile', 'Premium-1M')
 *       ->whereRegex('name', 'dyzulk')
 *       ->select(['name', 'limit-uptime'])
 *       ->get();
 */
class QueryBuilder
{
    /**
     * @var array<string, string>
     */
    protected array $params = [];

    public function __construct(
        protected Client $client,
        protected string $endpoint
    ) {}

    /**
     * Add an exact-match query filter.
     */
    public function where(string $key, string $value): self
    {
        $this->params["?{$key}"] = $value;

        return $this;
    }

    /**
     * Add a regex query filter.
     */
    public function whereRegex(string $key, string $pattern): self
    {
        $this->params["~{$key}"] = $pattern;

        return $this;
    }

    /**
     * Select specific attributes to return (.proplist).
     *
     * @param  array<int, string>  $attributes
     */
    public function select(array $attributes): self
    {
        $this->params['.proplist'] = implode(',', $attributes);

        return $this;
    }

    /**
     * Execute the query and return parsed results.
     *
     * @return array<int, array<string, string>>
     */
    public function get(): array
    {
        return $this->client->comm($this->endpoint, $this->params);
    }
}
