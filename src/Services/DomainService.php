<?php

declare(strict_types=1);

namespace Obelaw\Ium\Domain\Services;

final class DomainService
{
    public function records(): RecordManagementService
    {
        return app(RecordManagementService::class);
    }
}
