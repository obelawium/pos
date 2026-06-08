<?php

declare(strict_types=1);

namespace Obelaw\Ium\Domain\Base;

use Obelaw\Ium\Bases\MigrationBase as IumMigrationBase;

abstract class MigrationBase extends IumMigrationBase
{
    /**
     * Table postfix.
     *
     * @var string|null $module
     */
    protected ?string $module = 'dom_';
}
