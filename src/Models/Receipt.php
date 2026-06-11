<?php

declare(strict_types=1);

namespace Obelaw\Ium\Pos\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Obelaw\Ium\Pos\Base\ModelBase;

class Receipt extends ModelBase
{
    protected $fillable = [
        'session_id',
        'orderable_type',
        'orderable_id',
        'total_amount',
        'payment_method',
        'reference_no',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class, 'session_id');
    }
}
