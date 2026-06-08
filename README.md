# Obelawium IUM — Domain Module

> Baseline boilerplate for creating isolated domain modules within the Obelawium ERP ecosystem.

## Architecture

This module enforces **strict domain isolation**. Every domain is a self-contained Laravel package with no direct database cross-queries or model leakages.

```
ium()->domain()->records()->store($data);
```

### Principles

- **Domain Isolation** — No cross-domain DB queries or Model usage. Communication happens exclusively through Data objects.
- **Data Objects** — All incoming/outgoing payloads live under `Obelaw\Ium\Domain\Data`, providing type-safe, immutable structures.
- **Fluent Domain API** — Each domain registers a macro on `ObelawiumManager`, enabling seamless chaining via the global `ium()` helper.

## Directory Structure

```
database/migrations/          # Domain-scoped migrations
src/
  Base/                       # Abstract bases extending ium-core
  Data/                       # Type-safe data/transfer objects
  Models/                     # Eloquent models (never accessed cross-domain)
  Providers/                  # Service provider with macro registration
  Services/                   # Stateless service classes
```

## Usage

```php
$record = RecordData::fromArray([
    'key'   => 'setting_name',
    'value' => 'some_value',
    'options' => ['scope' => 'global'],
]);

ium()->domain()->records()->store($record);
```

## Installation

```bash
composer require obelaw/ium-domain
```

The service provider is auto-discovered via Laravel's package discovery.

## Extending

To create a new domain module, copy this boilerplate and:

1. Rename the namespace from `Domain` to your domain (e.g. `Pim`, `Wms`).
2. Update `composer.json` with the new package name and namespace.
3. Implement your own `Data`, `Models`, and `Services` following the same isolated patterns.
