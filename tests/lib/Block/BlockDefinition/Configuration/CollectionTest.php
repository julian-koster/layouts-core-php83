<?php

namespace Netgen\BlockManager\Tests\Block\BlockDefinition\Configuration;

use Netgen\BlockManager\Block\BlockDefinition\Configuration\Collection;
use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Block\BlockDefinition\Configuration\Collection
     */
    private $collection;

    public function setUp()
    {
        $this->collection = new Collection(
            [
                'identifier' => 'collection',
                'validItemTypes' => ['item'],
                'validQueryTypes' => ['query'],
            ]
        );
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition\Configuration\Collection::getIdentifier
     */
    public function testGetIdentifier()
    {
        $this->assertEquals('collection', $this->collection->getIdentifier());
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition\Configuration\Collection::getValidQueryTypes
     */
    public function testGetValidQueryTypes()
    {
        $this->assertEquals(['query'], $this->collection->getValidQueryTypes());
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition\Configuration\Collection::isValidQueryType
     */
    public function testIsValidQueryType()
    {
        $this->assertTrue($this->collection->isValidQueryType('query'));
        $this->assertFalse($this->collection->isValidQueryType('query2'));
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition\Configuration\Collection::isValidQueryType
     */
    public function testIsValidQueryTypeWithAllValidTypes()
    {
        $this->collection = new Collection(
            [
                'validQueryTypes' => null,
            ]
        );

        $this->assertTrue($this->collection->isValidQueryType('query'));
        $this->assertTrue($this->collection->isValidQueryType('query2'));
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition\Configuration\Collection::isValidQueryType
     */
    public function testIsValidQueryTypeWithNoValidTypes()
    {
        $this->collection = new Collection(
            [
                'validQueryTypes' => [],
            ]
        );

        $this->assertFalse($this->collection->isValidQueryType('query'));
        $this->assertFalse($this->collection->isValidQueryType('query2'));
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition\Configuration\Collection::getValidItemTypes
     */
    public function testGetValidItemTypes()
    {
        $this->assertEquals(['item'], $this->collection->getValidItemTypes());
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition\Configuration\Collection::isValidItemType
     */
    public function testIsValidItemType()
    {
        $this->assertTrue($this->collection->isValidItemType('item'));
        $this->assertFalse($this->collection->isValidItemType('item2'));
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition\Configuration\Collection::isValidItemType
     */
    public function testIsValidItemTypeWithAllValidTypes()
    {
        $this->collection = new Collection(
            [
                'validItemTypes' => null,
            ]
        );

        $this->assertTrue($this->collection->isValidItemType('item'));
        $this->assertTrue($this->collection->isValidItemType('item2'));
    }

    /**
     * @covers \Netgen\BlockManager\Block\BlockDefinition\Configuration\Collection::isValidItemType
     */
    public function testIsValidItemTypeWithNoValidTypes()
    {
        $this->collection = new Collection(
            [
                'validItemTypes' => [],
            ]
        );

        $this->assertFalse($this->collection->isValidItemType('item'));
        $this->assertFalse($this->collection->isValidItemType('item2'));
    }
}
