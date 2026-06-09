# Obelawium IUM — Pos Module

> Baseline boilerplate for creating isolated pos modules within the Obelawium ERP ecosystem.

## Architecture

This module enforces **strict pos isolation**. Every pos is a self-contained Laravel package with no direct database cross-queries or model leakages.

```
ium()->pos()->records()->store($data);
```

### Principles

- **Pos Isolation** — No cross-pos DB queries or Model usage. Communication happens exclusively through Data objects.
- **Data Objects** — All incoming/outgoing payloads live under `Obelaw\Ium\Pos\Data`, providing type-safe, immutable structures.
- **Fluent Pos API** — Each pos registers a macro on `ObelawiumManager`, enabling seamless chaining via the global `ium()` helper.

## Directory Structure

```
database/migrations/          # Pos-scoped migrations
src/
  Base/                       # Abstract bases extending ium-core
  Data/                       # Type-safe data/transfer objects
  Models/                     # Eloquent models (never accessed cross-pos)
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

ium()->pos()->records()->store($record);
```

## Installation

```bash
composer require obelaw/ium-pos
```

The service provider is auto-discovered via Laravel's package discovery.

## Extending

To create a new pos module, copy this boilerplate and:

1. Rename the namespace from `Pos` to your pos (e.g. `Pim`, `Wms`).
2. Update `composer.json` with the new package name and namespace.
3. Implement your own `Data`, `Models`, and `Services` following the same isolated patterns.
