<?php

declare(strict_types=1);

namespace Obelaw\Ium\Pos\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Obelaw\Ium\Pos\Base\ModelBase;
use Obelaw\Ium\Pos\Models\Receipt;
use Obelaw\Ium\Pos\Models\Terminal;

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
        return $this->belongsTo(Terminal::class, 'terminal_id');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class, 'session_id');
    }
}
