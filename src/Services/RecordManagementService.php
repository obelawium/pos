<?php

declare(strict_types=1);

namespace Obelaw\Ium\Pos\Services;

use Obelaw\Ium\Pos\Data\RecordData;
use Obelaw\Ium\Pos\Models\PosRecord;

final class RecordManagementService
{
    public function store(RecordData $data): bool
    {
        PosRecord::updateOrCreate(
            ['key' => $data->key],
            $data->toData(),
        );

        return true;
    }
}
