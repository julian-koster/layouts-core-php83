<?php

namespace Netgen\Bundle\BlockManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

abstract class Controller extends BaseController
{
    /**
     * Returns the specified block definition from the registry.
     *
     * @param string $definitionIdentifier
     *
     * @return \Netgen\BlockManager\Block\BlockDefinitionInterface
     */
    protected function getBlockDefinition($definitionIdentifier)
    {
        $blockDefinitionRegistry = $this->get('netgen_block_manager.block.registry.block_definition');

        return $blockDefinitionRegistry->getBlockDefinition($definitionIdentifier);
    }
}
