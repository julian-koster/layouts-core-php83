<?php

namespace Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\LayoutResolver;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use RuntimeException;

class DoctrineTargetHandlerPass implements CompilerPassInterface
{
    const SERVICE_NAME = 'netgen_block_manager.persistence.doctrine.layout_resolver.query_handler';
    const TAG_NAME = 'netgen_block_manager.persistence.doctrine.layout_resolver.query_handler.target_handler';

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(self::SERVICE_NAME)) {
            return;
        }

        $layoutResolverQueryHandler = $container->findDefinition(self::SERVICE_NAME);
        $targetHandlers = array();

        foreach ($container->findTaggedServiceIds(self::TAG_NAME) as $targetHandler => $tag) {
            if (!isset($tag[0]['identifier'])) {
                throw new RuntimeException('Doctrine target handler service tags should have an "identifier" attribute.');
            }

            $targetHandlers[$tag[0]['identifier']] = new Reference($targetHandler);
        }

        $layoutResolverQueryHandler->replaceArgument(2, $targetHandlers);
    }
}
