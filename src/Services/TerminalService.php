<?php

declare(strict_types=1);

namespace Obelaw\Ium\Pos\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Obelaw\Ium\Pos\Models\Terminal;
use Obelaw\Ium\Pos\Data\TerminalData;

/**
 * Terminal Service
 *
 * Manages all POS terminal operations including creation, updates, staff assignment,
 * and polymorphic terminalable relationships.
 *
 * @package Obelaw\Ium\Pos\Services
 */
final class TerminalService
{
    // ====== CRUD Operations ======

    /**
     * Create a terminal with TerminalData DTO
     *
     * Provides type-safe terminal creation with validation through the TerminalData object.
     * This is the only way to create terminals, ensuring data consistency and validation.
     *
     * @param TerminalData $data Validated terminal data
     * @return Terminal The created terminal instance
     */
    public function create(TerminalData $data): Terminal
    {
        return Terminal::create($data->toArray());
    }

    /**
     * Update a terminal
     *
     * @param Terminal $terminal The terminal to update
     * @param array $data Updated terminal attributes
     * @return bool True if update was successful
     */
    public function update(Terminal $terminal, array $data): bool
    {
        return $terminal->update($data);
    }

    /**
     * Update a terminal with TerminalData DTO
     *
     * Provides type-safe terminal updates with validation.
     *
     * @param Terminal $terminal The terminal to update
     * @param TerminalData $data Validated terminal data
     * @return bool True if update was successful
     */
    public function updateFromData(Terminal $terminal, TerminalData $data): bool
    {
        return $this->update($terminal, $data->toArray());
    }

    /**
     * Delete a terminal
     *
     * @param Terminal $terminal The terminal to delete
     * @return bool|null True on success, null on failure
     */
    public function delete(Terminal $terminal): ?bool
    {
        return $terminal->delete();
    }

    // ====== Retrieval Operations ======

    /**
     * Get a terminal by ID
     *
     * @param int $id Terminal primary key
     * @return Terminal|null The terminal instance or null if not found
     */
    public function getById(int $id): ?Terminal
    {
        return Terminal::find($id);
    }

    /**
     * Get a terminal by name
     *
     * @param string $name Terminal name
     * @return Terminal|null The terminal instance or null if not found
     */
    public function getByName(string $name): ?Terminal
    {
        return Terminal::where('name', $name)->first();
    }

    /**
     * Get all terminals
     *
     * @return Collection Collection of all terminal instances
     */
    public function getAll(): Collection
    {
        return Terminal::all();
    }

    /**
     * Get terminals paginated
     *
     * @param int $perPage Number of terminals per page (default: 15)
     * @return Paginator Paginated terminal collection
     */
    public function getPaginated(int $perPage = 15): Paginator
    {
        return Terminal::simplePaginate($perPage);
    }

    /**
     * Get terminals by status
     *
     * @param string $status Terminal status (active, inactive, maintenance)
     * @return Collection Collection of terminals matching the status
     */
    public function getByStatus(string $status): Collection
    {
        return Terminal::where('status', $status)->get();
    }

    /**
     * Check if a terminal exists by ID
     *
     * @param int $id Terminal primary key
     * @return bool True if terminal exists, false otherwise
     */
    public function exists(int $id): bool
    {
        return Terminal::where('id', $id)->exists();
    }

    // ====== Status Management ======

    /**
     * Activate a terminal
     *
     * Sets terminal status to 'active'.
     *
     * @param Terminal $terminal The terminal to activate
     * @return bool True if activation was successful
     */
    public function activate(Terminal $terminal): bool
    {
        return $this->update($terminal, ['status' => 'active']);
    }

    /**
     * Deactivate a terminal
     *
     * Sets terminal status to 'inactive'.
     *
     * @param Terminal $terminal The terminal to deactivate
     * @return bool True if deactivation was successful
     */
    public function deactivate(Terminal $terminal): bool
    {
        return $this->update($terminal, ['status' => 'inactive']);
    }

    // ====== Staff Management ======

    /**
     * Assign staff to terminal
     *
     * Attaches a staff member to a terminal if not already assigned.
     *
     * @param Terminal $terminal The terminal
     * @param int $staffId The staff member's user ID
     * @return void
     */
    public function assignStaff(Terminal $terminal, int $staffId): void
    {
        if (!$terminal->staff()->where('staff_id', $staffId)->exists()) {
            $terminal->staff()->attach($staffId, ['assigned_at' => now()]);
        }
    }

    /**
     * Remove staff from terminal
     *
     * Detaches a staff member from a terminal.
     *
     * @param Terminal $terminal The terminal
     * @param int $staffId The staff member's user ID
     * @return void
     */
    public function removeStaff(Terminal $terminal, int $staffId): void
    {
        $terminal->staff()->detach($staffId);
    }

    /**
     * Get all staff assigned to terminal
     *
     * @param Terminal $terminal The terminal
     * @return Collection Collection of assigned staff members
     */
    public function getStaff(Terminal $terminal): Collection
    {
        return $terminal->staff;
    }

    // ====== Terminalable Operations ======

    /**
     * Attach terminalable to terminal
     *
     * Associates a polymorphic model with the terminal.
     *
     * @param Terminal $terminal The terminal
     * @param object $terminalable The polymorphic model instance with 'id' property
     * @return Terminal The updated terminal instance
     */
    public function attachTerminalable(Terminal $terminal, object $terminalable): Terminal
    {
        return tap($terminal, function (Terminal $t) use ($terminalable): void {
            $t->terminalable_type = $terminalable::class;
            $t->terminalable_id = $terminalable->id;
            $t->save();
        })->fresh();
    }

    /**
     * Update terminal's terminalable relationship
     *
     * Updates the polymorphic relationship on an existing terminal.
     *
     * @param Terminal $terminal The terminal
     * @param object $terminalable The new polymorphic model instance
     * @return bool True if update was successful
     */
    public function updateTerminalable(Terminal $terminal, object $terminalable): bool
    {
        return $this->update($terminal, [
            'terminalable_type' => $terminalable::class,
            'terminalable_id' => $terminalable->id,
        ]);
    }

    /**
     * Detach terminalable from terminal
     *
     * Removes the polymorphic relationship from the terminal.
     *
     * @param Terminal $terminal The terminal
     * @return bool True if detachment was successful
     */
    public function detachTerminalable(Terminal $terminal): bool
    {
        return $this->update($terminal, [
            'terminalable_type' => null,
            'terminalable_id' => null,
        ]);
    }

    /**
     * Get terminal's terminalable model
     *
     * Retrieves the associated polymorphic model if exists.
     *
     * @param Terminal $terminal The terminal
     * @return object|null The terminalable model instance or null if not set
     */
    public function getTerminalable(Terminal $terminal): ?object
    {
        return $terminal->terminalable;
    }

    /**
     * Check if terminal has terminalable
     *
     * Verifies if a terminalable relationship is configured.
     *
     * @param Terminal $terminal The terminal
     * @return bool True if terminal has both terminalable_type and terminalable_id set
     */
    public function hasTerminalable(Terminal $terminal): bool
    {
        return $terminal->terminalable_id !== null && $terminal->terminalable_type !== null;
    }

    // ====== Related Data ======

    /**
     * Get all sessions for terminal
     *
     * @param Terminal $terminal The terminal
     * @return Collection Collection of session instances
     */
    public function getSessions(Terminal $terminal): Collection
    {
        return $terminal->sessions;
    }
}