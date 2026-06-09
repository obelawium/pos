<?php

declare(strict_types=1);

namespace Obelaw\Ium\Pos\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Obelaw\Ium\Pos\Base\ModelBase;

class Session extends ModelBase
{
    protected $fillable = [
        'terminal_id',
        'status',
        'opened_at',
        'closed_at',
        'opening_balance',
        'closing_balance',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'opening_balance' => 'decimal:2',
            'closing_balance' => 'decimal:2',
        ];
    }

    public function terminal(): BelongsTo
    {
        return $this->belongsTo(PosTerminal::class, 'terminal_id');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(PosReceipt::class, 'session_id');
    }
}
