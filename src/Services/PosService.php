<?php

declare(strict_types=1);

namespace Obelaw\Ium\Pos\Services;

use Obelaw\Ium\Abstracts\Domain;

final class PosService extends Domain
{
    public function terminal(): TerminalService
    {
        return new TerminalService();
    }
}
