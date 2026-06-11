<?php

declare(strict_types=1);

namespace Obelaw\Ium\Pos\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Obelaw\Ium\Pos\Base\ModelBase;
use Obelaw\Ium\Pos\Models\Session;

class Terminal extends ModelBase
{
    protected $fillable = [
        'name',
        'terminalable_type',
        'terminalable_id',
        'status',
    ];

    public function terminalable(): MorphTo
    {
        return $this->morphTo();
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'terminal_id');
    }

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\User::class,
            'ium_pos_terminal_staff',
            'terminal_id',
            'staff_id',
        )->withPivot('assigned_at')->withTimestamps();
    }
}
