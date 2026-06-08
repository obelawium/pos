<?php

declare(strict_types=1);

namespace Obelaw\Ium\Domain\Data;

final class RecordData
{
    public function __construct(
        public readonly string $key,
        public readonly string $value,
        public readonly array $options = [],
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            key: $data['key'],
            value: $data['value'],
            options: $data['options'] ?? [],
        );
    }

    public function toData(): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
            'extra_custom' => $this->options,
        ];
    }
}
