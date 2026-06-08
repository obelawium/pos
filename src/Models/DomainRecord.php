<?php

declare(strict_types=1);

namespace Obelaw\Ium\Domain\Models;

use Obelaw\Ium\Domain\Base\ModelBase;

final class DomainRecord extends ModelBase
{
    protected $table = 'ium_domain_records';

    protected $fillable = [
        'key',
        'value',
        'extra_custom',
    ];

    protected function casts(): array
    {
        return [
            'extra_custom' => 'array',
        ];
    }
}
