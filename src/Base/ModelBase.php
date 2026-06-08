<?php

declare(strict_types=1);

namespace Obelaw\Ium\Domain\Base;

use Obelaw\Ium\Bases\ModelBase as IumModelBase;

abstract class ModelBase extends IumModelBase
{
    /**
     * Table postfix.
     *
     * @var string|null $module
     */
    protected ?string $module = 'dom_';
}
