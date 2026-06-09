<?php

declare(strict_types=1);

namespace Obelaw\Ium\Pos\Base;

use Obelaw\Ium\Bases\MigrationBase as IumMigrationBase;

abstract class MigrationBase extends IumMigrationBase
{
    protected ?string $module = 'pos_';
}
