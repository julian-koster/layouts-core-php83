<?php

namespace Netgen\BlockManager\Tests\Item\Registry;

use Netgen\BlockManager\Tests\Item\Stubs\ValueLoader;
use Netgen\BlockManager\Item\Registry\ValueLoaderRegistry;

class ValueLoaderRegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Netgen\BlockManager\Item\ValueLoaderInterface
     */
    protected $valueLoader;

    /**
     * @var \Netgen\BlockManager\Item\Registry\ValueLoaderRegistry
     */
    protected $registry;

    public function setUp()
    {
        $this->registry = new ValueLoaderRegistry();

        $this->valueLoader = new ValueLoader();
        $this->registry->addValueLoader($this->valueLoader);
    }

    /**
     * @covers \Netgen\BlockManager\Item\Registry\ValueLoaderRegistry::addValueLoader
     * @covers \Netgen\BlockManager\Item\Registry\ValueLoaderRegistry::getValueLoaders
     */
    public function testAddValueLoader()
    {
        self::assertEquals(array('value' => $this->valueLoader), $this->registry->getValueLoaders());
    }

    /**
     * @covers \Netgen\BlockManager\Item\Registry\ValueLoaderRegistry::getValueLoader
     */
    public function testGetValueLoader()
    {
        self::assertEquals($this->valueLoader, $this->registry->getValueLoader('value'));
    }

    /**
     * @covers \Netgen\BlockManager\Item\Registry\ValueLoaderRegistry::getValueLoader
     * @expectedException \Netgen\BlockManager\Exception\NotFoundException
     */
    public function testGetValueLoaderThrowsNotFoundException()
    {
        $this->registry->getValueLoader('other_value');
    }

    /**
     * @covers \Netgen\BlockManager\Item\Registry\ValueLoaderRegistry::hasValueLoader
     */
    public function testHasValueLoader()
    {
        self::assertTrue($this->registry->hasValueLoader('value'));
    }

    /**
     * @covers \Netgen\BlockManager\Item\Registry\ValueLoaderRegistry::hasValueLoader
     */
    public function testHasValueLoaderWithNoValueLoader()
    {
        self::assertFalse($this->registry->hasValueLoader('other_value'));
    }
}
