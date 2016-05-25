<?php

namespace Netgen\BlockManager\Core\Values\LayoutResolver;

use Netgen\BlockManager\API\Values\LayoutResolver\Rule as APIRule;
use Netgen\BlockManager\ValueObject;

class Rule extends ValueObject implements APIRule
{
    /**
     * @var int|string
     */
    protected $id;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var int|string
     */
    protected $layoutId;

    /**
     * @var bool
     */
    protected $enabled = false;

    /**
     * @var int
     */
    protected $priority;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var \Netgen\BlockManager\API\Values\LayoutResolver\Target[]
     */
    protected $targets = array();

    /**
     * @var \Netgen\BlockManager\Layout\Resolver\Condition[]
     */
    protected $conditions = array();

    /**
     * Returns the rule ID.
     *
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the rule status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns resolved layout ID.
     *
     * @return int|string
     */
    public function getLayoutId()
    {
        return $this->layoutId;
    }

    /**
     * Returns if the rule is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Returns the rule priority.
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Returns the rule comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Returns the target this rule applies to.
     *
     * @return \Netgen\BlockManager\API\Values\LayoutResolver\Target[]
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * Returns rule conditions.
     *
     * @return \Netgen\BlockManager\Layout\Resolver\Condition[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }
}
