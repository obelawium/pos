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

### Records

```php
$record = RecordData::fromArray([
    'key'   => 'setting_name',
    'value' => 'some_value',
    'options' => ['scope' => 'global'],
]);

ium()->pos()->records()->store($record);
```

### Terminals

The Terminal Service manages POS terminals with support for staff assignment, status management, and polymorphic terminalable relationships.

#### Creating Terminals

Terminals must be created using the `TerminalData` DTO for type safety and validation:

```php
use Obelaw\Ium\Pos\Data\TerminalData;

// Create a simple terminal
$terminalData = new TerminalData(
    name: 'Main Counter',
    status: 'active'
);

$terminal = ium()->pos()->terminal()->create($terminalData);

// Create a terminal with a polymorphic relationship
$store = Store::find(1);
$terminalData = new TerminalData(
    name: 'Main Counter',
    status: 'active',
    terminalable: $store
);

$terminal = ium()->pos()->terminal()->create($terminalData);
```

#### Retrieving Terminals

```php
// Get terminal by ID
$terminal = ium()->pos()->terminal()->getById(1);

// Get terminal by name
$terminal = ium()->pos()->terminal()->getByName('Main Counter');

// Get all terminals
$terminals = ium()->pos()->terminal()->getAll();

// Get terminals paginated
$paginated = ium()->pos()->terminal()->getPaginated(perPage: 20);

// Get terminals by status
$activeTerminals = ium()->pos()->terminal()->getByStatus('active');

// Check if terminal exists
$exists = ium()->pos()->terminal()->exists(1);
```

#### Updating Terminals

```php
// Update with TerminalData (recommended)
$updatedData = new TerminalData(
    name: 'Updated Counter Name',
    status: 'active'
);

ium()->pos()->terminal()->updateFromData($terminal, $updatedData);

// Update raw data
ium()->pos()->terminal()->update($terminal, [
    'name' => 'New Name',
    'status' => 'inactive'
]);
```

#### Status Management

```php
// Activate a terminal
ium()->pos()->terminal()->activate($terminal);

// Deactivate a terminal
ium()->pos()->terminal()->deactivate($terminal);
```

#### Staff Assignment

```php
// Assign staff to terminal
$staffId = 5;
ium()->pos()->terminal()->assignStaff($terminal, $staffId);

// Remove staff from terminal
ium()->pos()->terminal()->removeStaff($terminal, $staffId);

// Get all staff assigned to terminal
$staff = ium()->pos()->terminal()->getStaff($terminal);
```

#### Terminalable Relationships

The terminal supports polymorphic relationships with any model through the `terminalable` association:

```php
// Attach terminalable to terminal
$store = Store::find(1);
ium()->pos()->terminal()->attachTerminalable($terminal, $store);

// Update terminalable relationship
$newStore = Store::find(2);
ium()->pos()->terminal()->updateTerminalable($terminal, $newStore);

// Get terminal's terminalable model
$store = ium()->pos()->terminal()->getTerminalable($terminal);

// Check if terminal has terminalable
$hasTerminalable = ium()->pos()->terminal()->hasTerminalable($terminal);

// Detach terminalable from terminal
ium()->pos()->terminal()->detachTerminalable($terminal);
```

#### Related Data

```php
// Get all sessions for a terminal
$sessions = ium()->pos()->terminal()->getSessions($terminal);
```

#### Deleting Terminals

```php
$deleted = ium()->pos()->terminal()->delete($terminal);
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
