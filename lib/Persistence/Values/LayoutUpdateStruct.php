<?php

namespace Netgen\BlockManager\Persistence\Values;

use Netgen\BlockManager\ValueObject;

class LayoutUpdateStruct extends ValueObject
{
    /**
     * @var int
     */
    public $modified;

    /**
     * @var string
     */
    public $name;
}
