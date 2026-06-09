<?php

declare(strict_types=1);

namespace Obelaw\Ium\Pos\Services;

final class PosService
{
    public function records(): RecordManagementService
    {
        return app(RecordManagementService::class);
    }
}
