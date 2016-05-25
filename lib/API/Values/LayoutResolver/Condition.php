<?php

namespace Netgen\BlockManager\API\Values\LayoutResolver;

use Netgen\BlockManager\API\Values\Value;

interface Condition extends Value
{
    /**
     * Returns the condition ID.
     *
     * @return int|string
     */
    public function getId();

    /**
     * Returns the condition status.
     *
     * @return int
     */
    public function getStatus();

    /**
     * Returns the rule ID to which this condition belongs to.
     *
     * @return int|string
     */
    public function getRuleId();

    /**
     * Returns the identifier.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Returns the condition value.
     *
     * @return mixed
     */
    public function getValue();
}
