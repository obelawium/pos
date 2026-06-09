<?php

declare(strict_types=1);

namespace Obelaw\Ium\Pos\Models;

use Obelaw\Ium\Pos\Base\ModelBase;

final class PosRecord extends ModelBase
{
    protected $table = 'ium_pos_records';

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
