<?php

namespace Netgen\Bundle\BlockManagerBundle\Tests\Stubs;

use Netgen\Bundle\BlockManagerBundle\ParamConverter\ParamConverter as BaseParamConverter;
use Netgen\BlockManager\Tests\API\Stubs\Value;

class ParamConverter extends BaseParamConverter
{
    /**
     * Returns source attribute name.
     *
     * @return string
     */
    public function getSourceAttributeName()
    {
        return 'id';
    }

    /**
     * Returns destination attribute name.
     *
     * @return string
     */
    public function getDestinationAttributeName()
    {
        return 'value';
    }

    /**
     * Returns the supported class.
     *
     * @return string
     */
    public function getSupportedClass()
    {
        return 'Netgen\BlockManager\Tests\API\Stubs\Value';
    }

    /**
     * Returns the value object.
     *
     * @param int|string $valueId
     *
     * @return \Netgen\BlockManager\API\Values\Value
     */
    public function loadValueObject($valueId)
    {
        return new Value();
    }
}
