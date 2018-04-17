<?php

namespace Netgen\BlockManager\Tests\Block\Registry;

use Netgen\BlockManager\Block\BlockDefinition\BlockDefinitionHandlerInterface;
use Netgen\BlockManager\Block\Registry\HandlerPluginRegistry;
use Netgen\BlockManager\Tests\Block\Stubs\BlockDefinitionHandler;
use Netgen\BlockManager\Tests\Block\Stubs\HandlerPlugin;
use PHPUnit\Framework\TestCase;
use stdClass;

final class HandlerPluginRegistryTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Block\Registry\HandlerPluginRegistry
     */
    private $registry;

    public function setUp()
    {
        $this->registry = new HandlerPluginRegistry();
    }

    /**
     * @covers \Netgen\BlockManager\Block\Registry\HandlerPluginRegistry::addPlugin
     * @covers \Netgen\BlockManager\Block\Registry\HandlerPluginRegistry::getPlugins
     */
    public function testRegistry()
    {
        $handlerPlugin = HandlerPlugin::instance([BlockDefinitionHandler::class]);

        $this->registry->addPlugin($handlerPlugin);

        $this->assertEquals(
            [$handlerPlugin],
            $this->registry->getPlugins(BlockDefinitionHandler::class)
        );

        $this->assertEquals(
            [],
            $this->registry->getPlugins(stdClass::class)
        );
    }

    /**
     * @covers \Netgen\BlockManager\Block\Registry\HandlerPluginRegistry::addPlugin
     * @covers \Netgen\BlockManager\Block\Registry\HandlerPluginRegistry::getPlugins
     */
    public function testRegistryWithInterface()
    {
        $handlerPlugin = HandlerPlugin::instance([BlockDefinitionHandlerInterface::class]);

        $this->registry->addPlugin($handlerPlugin);

        $this->assertEquals(
            [$handlerPlugin],
            $this->registry->getPlugins(BlockDefinitionHandler::class)
        );

        $this->assertEquals(
            [],
            $this->registry->getPlugins(stdClass::class)
        );
    }
}
