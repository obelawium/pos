<?php

declare(strict_types=1);

namespace Obelaw\Ium\Pos\Data;

use InvalidArgumentException;

final class TerminalData
{
    public function __construct(
        public readonly string $name,
        public readonly string $status = 'active',
        public readonly ?object $terminalable = null,
    ) {
        if (trim($name) === '') {
            throw new InvalidArgumentException('Terminal name cannot be empty.');
        }

        $validStatuses = ['active', 'inactive', 'maintenance'];
        if (!in_array($status, $validStatuses, true)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid terminal status "%s". Allowed values: %s.',
                    $status,
                    implode(', ', $validStatuses),
                ),
            );
        }

        if ($terminalable !== null && !isset($terminalable->id)) {
            throw new InvalidArgumentException(
                'Terminalable object must have an id property.',
            );
        }
    }

    /**
     * Convert to array for model creation/update
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'status' => $this->status,
        ];

        if ($this->terminalable !== null) {
            $data['terminalable_type'] = $this->terminalable::class;
            $data['terminalable_id'] = $this->terminalable->id;
        }

        return $data;
    }

    /**
     * Create from array
     */
    public static function fromArray(array $data, ?object $terminalable = null): self
    {
        return new self(
            name: $data['name'] ?? throw new InvalidArgumentException('Terminal name is required.'),
            status: $data['status'] ?? 'active',
            terminalable: $terminalable,
        );
    }
}
