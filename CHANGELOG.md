# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.2.1] - 2026-05-28

### Added
- Flexible options/flags (`--host`, `--username`, `--password`, `--port`) in `RouterPingCommand` for quick testing.
- Interactive step-by-step console prompts in `RouterPingCommand` when no parameters are provided.
- Clarified `--host` option description in `--help` output to accept both IP addresses and Hostnames/Domains.

## [0.2.0] - 2026-05-28

### Added
- Complete set of 22 dedicated **Service Managers** providing clean sugar-syntax methods for core ISP/RouterOS features.
- Fluent **Query Builder** supporting conditional chaining (`where`, `whereRegex`, `select`, `get`).
- Dynanic Dual-Stack IPv4 / IPv6 network, firewall list, and route management methods inside respective managers.
- `RouterPingCommand` Artisan tool `mivo:ros6-ping` for active network diagnostics.

## [0.1.0] - 2026-05-26

### Added
- `MikrotikRos6ServiceProvider` for Laravel auto-discovery and config binding.
- `MikrotikRos6` Facade for easy global access to the API client.
- Published configuration file `mikrotik-ros6.php` with defaults mapping to `.env` variables.
