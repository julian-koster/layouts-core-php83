<?php

namespace Netgen\BlockManager\Configuration\LayoutType;

class LayoutType
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Netgen\BlockManager\Configuration\LayoutType\Zone[]
     */
    protected $zones = array();

    /**
     * Constructor.
     *
     * @param string $identifier
     * @param string $name
     * @param \Netgen\BlockManager\Configuration\LayoutType\Zone[] $zones
     */
    public function __construct($identifier, $name, array $zones)
    {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->zones = $zones;
    }

    /**
     * Returns the layout type identifier.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Returns the layout type name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the layout type zones.
     *
     * @return \Netgen\BlockManager\Configuration\LayoutType\Zone[]
     */
    public function getZones()
    {
        return $this->zones;
    }
}
