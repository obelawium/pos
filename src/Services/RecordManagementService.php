<?php

declare(strict_types=1);

namespace Obelaw\Ium\Domain\Services;

use Obelaw\Ium\Domain\Data\RecordData;
use Obelaw\Ium\Domain\Models\DomainRecord;

final class RecordManagementService
{
    public function store(RecordData $data): bool
    {
        DomainRecord::updateOrCreate(
            ['key' => $data->key],
            $data->toData(),
        );

        return true;
    }
}
