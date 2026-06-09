<?php

declare(strict_types=1);

namespace Obelaw\Ium\Pos\Base;

use Obelaw\Ium\Bases\ModelBase as IumModelBase;

abstract class ModelBase extends IumModelBase
{
    protected ?string $module = 'pos_';
}
