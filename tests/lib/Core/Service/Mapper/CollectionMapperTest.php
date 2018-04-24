<?php

namespace Netgen\BlockManager\Tests\Core\Service\Mapper;

use DateTimeImmutable;
use DateTimeZone;
use Netgen\BlockManager\API\Values\Collection\Collection as APICollection;
use Netgen\BlockManager\API\Values\Collection\Item as APIItem;
use Netgen\BlockManager\API\Values\Collection\Query as APIQuery;
use Netgen\BlockManager\API\Values\Config\Config;
use Netgen\BlockManager\API\Values\Value;
use Netgen\BlockManager\Collection\Item\NullItemDefinition;
use Netgen\BlockManager\Collection\NullQueryType;
use Netgen\BlockManager\Item\Item as CmsItem;
use Netgen\BlockManager\Persistence\Values\Collection\Collection;
use Netgen\BlockManager\Persistence\Values\Collection\Item;
use Netgen\BlockManager\Persistence\Values\Collection\Query;
use Netgen\BlockManager\Tests\Core\Service\ServiceTestCase;

abstract class CollectionMapperTest extends ServiceTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->collectionMapper = $this->createCollectionMapper();
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::__construct
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapCollection
     */
    public function testMapCollection()
    {
        $persistenceCollection = new Collection(
            [
                'id' => 2,
                'offset' => 10,
                'limit' => 20,
                'alwaysAvailable' => false,
                'isTranslatable' => true,
                'mainLocale' => 'en',
                'availableLocales' => ['en'],
                'status' => Value::STATUS_PUBLISHED,
            ]
        );

        $collection = $this->collectionMapper->mapCollection($persistenceCollection);

        $this->assertInstanceOf(APICollection::class, $collection);
        $this->assertEquals(2, $collection->getId());
        $this->assertEquals(APICollection::TYPE_DYNAMIC, $collection->getType());
        $this->assertEquals(Value::STATUS_PUBLISHED, $collection->getStatus());
        $this->assertEquals(10, $collection->getOffset());
        $this->assertEquals(20, $collection->getLimit());
        $this->assertTrue($collection->isPublished());
        $this->assertTrue($collection->isTranslatable());
        $this->assertEquals('en', $collection->getMainLocale());
        $this->assertFalse($collection->isAlwaysAvailable());
        $this->assertEquals(['en'], $collection->getAvailableLocales());

        foreach ($collection->getItems() as $item) {
            $this->assertInstanceOf(APIItem::class, $item);
        }

        foreach ($collection->getManualItems() as $item) {
            $this->assertInstanceOf(APIItem::class, $item);
        }

        foreach ($collection->getOverrideItems() as $item) {
            $this->assertInstanceOf(APIItem::class, $item);
        }

        $this->assertEquals(
            count($collection->getItems()),
            count($collection->getManualItems()) + count($collection->getOverrideItems())
        );

        $this->assertInstanceOf(APIQuery::class, $collection->getQuery());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::__construct
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapCollection
     */
    public function testMapCollectionWithLocale()
    {
        $persistenceCollection = new Collection(
            [
                'mainLocale' => 'en',
                'availableLocales' => ['en', 'hr', 'de'],
            ]
        );

        $collection = $this->collectionMapper->mapCollection($persistenceCollection, ['hr']);

        $this->assertInstanceOf(APICollection::class, $collection);
        $this->assertEquals(['en', 'hr', 'de'], $collection->getAvailableLocales());
        $this->assertEquals('hr', $collection->getLocale());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::__construct
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapCollection
     */
    public function testMapCollectionWithLocales()
    {
        $persistenceCollection = new Collection(
            [
                'mainLocale' => 'en',
                'availableLocales' => ['en', 'hr', 'de'],
            ]
        );

        $collection = $this->collectionMapper->mapCollection($persistenceCollection, ['hr', 'en']);

        $this->assertInstanceOf(APICollection::class, $collection);
        $this->assertEquals(['en', 'hr', 'de'], $collection->getAvailableLocales());
        $this->assertEquals('hr', $collection->getLocale());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::__construct
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapCollection
     */
    public function testMapCollectionWithLocalesAndAlwaysAvailable()
    {
        $persistenceCollection = new Collection(
            [
                'mainLocale' => 'en',
                'alwaysAvailable' => true,
                'availableLocales' => ['en', 'hr', 'de'],
            ]
        );

        $collection = $this->collectionMapper->mapCollection($persistenceCollection, ['fr', 'no']);

        $this->assertInstanceOf(APICollection::class, $collection);
        $this->assertEquals(['en', 'hr', 'de'], $collection->getAvailableLocales());
        $this->assertEquals('en', $collection->getLocale());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::__construct
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapCollection
     * @expectedException \Netgen\BlockManager\Exception\NotFoundException
     * @expectedExceptionMessage Could not find collection with identifier "42"
     */
    public function testMapCollectionWithLocalesAndAlwaysAvailableWithoutUsingMainLocale()
    {
        $persistenceCollection = new Collection(
            [
                'id' => 42,
                'mainLocale' => 'en',
                'alwaysAvailable' => true,
                'availableLocales' => ['en', 'hr', 'de'],
            ]
        );

        $this->collectionMapper->mapCollection($persistenceCollection, ['fr', 'no'], false);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::__construct
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapCollection
     * @expectedException \Netgen\BlockManager\Exception\NotFoundException
     * @expectedExceptionMessage Could not find collection with identifier "42"
     */
    public function testMapCollectionWithLocalesAndNotAlwaysAvailable()
    {
        $persistenceCollection = new Collection(
            [
                'id' => 42,
                'mainLocale' => 'en',
                'alwaysAvailable' => false,
                'availableLocales' => ['en', 'hr', 'de'],
            ]
        );

        $this->collectionMapper->mapCollection($persistenceCollection, ['fr', 'no']);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapCollection
     */
    public function testMapCollectionWithNoQuery()
    {
        $persistenceCollection = new Collection(
            [
                'id' => 1,
                'offset' => 10,
                'limit' => 20,
                'alwaysAvailable' => false,
                'isTranslatable' => true,
                'mainLocale' => 'en',
                'availableLocales' => ['en'],
                'status' => Value::STATUS_PUBLISHED,
            ]
        );

        $collection = $this->collectionMapper->mapCollection($persistenceCollection);

        $this->assertInstanceOf(APICollection::class, $collection);
        $this->assertEquals(1, $collection->getId());
        $this->assertEquals(APICollection::TYPE_MANUAL, $collection->getType());
        $this->assertEquals(Value::STATUS_PUBLISHED, $collection->getStatus());
        $this->assertEquals(0, $collection->getOffset());
        $this->assertEquals(20, $collection->getLimit());
        $this->assertTrue($collection->isPublished());
        $this->assertTrue($collection->isTranslatable());
        $this->assertEquals('en', $collection->getMainLocale());
        $this->assertFalse($collection->isAlwaysAvailable());
        $this->assertEquals(['en'], $collection->getAvailableLocales());

        foreach ($collection->getItems() as $item) {
            $this->assertInstanceOf(APIItem::class, $item);
        }

        foreach ($collection->getManualItems() as $item) {
            $this->assertInstanceOf(APIItem::class, $item);
        }

        foreach ($collection->getOverrideItems() as $item) {
            $this->assertInstanceOf(APIItem::class, $item);
        }

        $this->assertEquals(
            count($collection->getItems()),
            count($collection->getManualItems()) + count($collection->getOverrideItems())
        );

        $this->assertNull($collection->getQuery());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapItem
     */
    public function testMapItem()
    {
        $persistenceItem = new Item(
            [
                'id' => 1,
                'status' => Value::STATUS_PUBLISHED,
                'collectionId' => 42,
                'position' => 1,
                'type' => APIItem::TYPE_OVERRIDE,
                'value' => '12',
                'valueType' => 'ezcontent',
                'config' => [
                    'visibility' => [
                        'visibility_status' => APIItem::VISIBILITY_SCHEDULED,
                        'visible_from' => null,
                        'visible_to' => [
                            'datetime' => '2018-02-01 15:00:00.000000',
                            'timezone' => 'Antarctica/Casey',
                        ],
                    ],
                ],
            ]
        );

        $item = $this->collectionMapper->mapItem($persistenceItem);

        $this->assertInstanceOf(APIItem::class, $item);
        $this->assertEquals(1, $item->getId());
        $this->assertEquals(42, $item->getCollectionId());
        $this->assertEquals($this->itemDefinitionRegistry->getItemDefinition('ezcontent'), $item->getDefinition());
        $this->assertEquals(1, $item->getPosition());
        $this->assertEquals(APIItem::TYPE_OVERRIDE, $item->getType());
        $this->assertEquals('12', $item->getValue());
        $this->assertEquals(new CmsItem(), $item->getCmsItem());
        $this->assertEquals(Value::STATUS_PUBLISHED, $item->getStatus());
        $this->assertTrue($item->isPublished());

        $this->assertTrue($item->hasConfig('visibility'));
        $this->assertInstanceOf(Config::class, $item->getConfig('visibility'));

        $visibilityConfig = $item->getConfig('visibility');

        $this->assertEquals(APIItem::VISIBILITY_SCHEDULED, $visibilityConfig->getParameter('visibility_status')->getValue());
        $this->assertNull($visibilityConfig->getParameter('visible_from')->getValue());
        $this->assertEquals(
            new DateTimeImmutable('2018-02-01 15:00:00', new DateTimeZone('Antarctica/Casey')),
            $visibilityConfig->getParameter('visible_to')->getValue()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapItem
     */
    public function testMapItemWithInvalidItemDefinition()
    {
        $persistenceItem = new Item(
            [
                'id' => 1,
                'status' => Value::STATUS_PUBLISHED,
                'collectionId' => 42,
                'position' => 1,
                'type' => APIItem::TYPE_OVERRIDE,
                'value' => '12',
                'valueType' => 'unknown',
                'config' => [
                    'visibility' => [
                        'visibility_status' => APIItem::VISIBILITY_SCHEDULED,
                        'visible_from' => null,
                        'visible_to' => [
                            'datetime' => '2018-02-01 15:00:00.000000',
                            'timezone' => 'Antarctica/Casey',
                        ],
                    ],
                ],
            ]
        );

        $item = $this->collectionMapper->mapItem($persistenceItem);

        $this->assertInstanceOf(APIItem::class, $item);
        $this->assertEquals(1, $item->getId());
        $this->assertEquals(42, $item->getCollectionId());
        $this->assertInstanceOf(NullItemDefinition::class, $item->getDefinition());
        $this->assertEquals(1, $item->getPosition());
        $this->assertEquals(APIItem::TYPE_OVERRIDE, $item->getType());
        $this->assertEquals('12', $item->getValue());
        $this->assertEquals(new CmsItem(), $item->getCmsItem());
        $this->assertEquals(Value::STATUS_PUBLISHED, $item->getStatus());
        $this->assertTrue($item->isPublished());

        $this->assertFalse($item->hasConfig('visibility'));
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapQuery
     */
    public function testMapQuery()
    {
        $persistenceQuery = new Query(
            [
                'id' => 1,
                'status' => Value::STATUS_PUBLISHED,
                'collectionId' => 42,
                'type' => 'ezcontent_search',
                'alwaysAvailable' => false,
                'isTranslatable' => true,
                'mainLocale' => 'en',
                'availableLocales' => ['en'],
                'parameters' => [
                    'en' => [
                        'param' => 'value',
                    ],
                ],
            ]
        );

        $query = $this->collectionMapper->mapQuery($persistenceQuery);

        $this->assertEquals(
            $this->queryTypeRegistry->getQueryType('ezcontent_search'),
            $query->getQueryType()
        );

        $this->assertInstanceOf(APIQuery::class, $query);
        $this->assertEquals(1, $query->getId());
        $this->assertEquals(42, $query->getCollectionId());
        $this->assertEquals(Value::STATUS_PUBLISHED, $query->getStatus());
        $this->assertTrue($query->isPublished());
        $this->assertTrue($query->isTranslatable());
        $this->assertEquals('en', $query->getMainLocale());
        $this->assertFalse($query->isAlwaysAvailable());
        $this->assertEquals(['en'], $query->getAvailableLocales());

        $this->assertEquals('value', $query->getParameter('param')->getValue());
        $this->assertNull($query->getParameter('param2')->getValue());

        $this->assertEquals('en', $query->getLocale());

        $this->assertEquals('value', $query->getParameter('param')->getValue());
        $this->assertNull($query->getParameter('param2')->getValue());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::__construct
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapQuery
     */
    public function testMapQueryWithLocale()
    {
        $persistenceQuery = new Query(
            [
                'type' => 'ezcontent_search',
                'mainLocale' => 'en',
                'availableLocales' => ['en', 'hr', 'de'],
                'parameters' => ['en' => [], 'hr' => [], 'de' => []],
            ]
        );

        $query = $this->collectionMapper->mapQuery($persistenceQuery, ['hr']);

        $this->assertInstanceOf(APIQuery::class, $query);
        $this->assertEquals(['en', 'hr', 'de'], $query->getAvailableLocales());
        $this->assertEquals('hr', $query->getLocale());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::__construct
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapQuery
     */
    public function testMapQueryWithLocales()
    {
        $persistenceQuery = new Query(
            [
                'type' => 'ezcontent_search',
                'mainLocale' => 'en',
                'availableLocales' => ['en', 'hr', 'de'],
                'parameters' => ['en' => [], 'hr' => [], 'de' => []],
            ]
        );

        $query = $this->collectionMapper->mapQuery($persistenceQuery, ['hr', 'en']);

        $this->assertInstanceOf(APIQuery::class, $query);
        $this->assertEquals(['en', 'hr', 'de'], $query->getAvailableLocales());
        $this->assertEquals('hr', $query->getLocale());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::__construct
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapQuery
     */
    public function testMapQueryWithLocalesAndAlwaysAvailable()
    {
        $persistenceQuery = new Query(
            [
                'type' => 'ezcontent_search',
                'alwaysAvailable' => true,
                'mainLocale' => 'en',
                'availableLocales' => ['en', 'hr', 'de'],
                'parameters' => ['en' => [], 'hr' => [], 'de' => []],
            ]
        );

        $query = $this->collectionMapper->mapQuery($persistenceQuery, ['fr', 'no']);

        $this->assertInstanceOf(APIQuery::class, $query);
        $this->assertEquals(['en', 'hr', 'de'], $query->getAvailableLocales());
        $this->assertEquals('en', $query->getLocale());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::__construct
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapQuery
     * @expectedException \Netgen\BlockManager\Exception\NotFoundException
     * @expectedExceptionMessage Could not find query with identifier "42"
     */
    public function testMapQueryWithLocalesAndAlwaysAvailableWithoutUsingMainLocale()
    {
        $persistenceQuery = new Query(
            [
                'id' => 42,
                'type' => 'ezcontent_search',
                'alwaysAvailable' => true,
                'mainLocale' => 'en',
                'availableLocales' => ['en', 'hr', 'de'],
                'parameters' => ['en' => [], 'hr' => [], 'de' => []],
            ]
        );

        $this->collectionMapper->mapQuery($persistenceQuery, ['fr', 'no'], false);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::__construct
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapQuery
     * @expectedException \Netgen\BlockManager\Exception\NotFoundException
     * @expectedExceptionMessage Could not find query with identifier "42"
     */
    public function testMapQueryWithLocalesAndNotAlwaysAvailable()
    {
        $persistenceQuery = new Query(
            [
                'id' => 42,
                'type' => 'ezcontent_search',
                'alwaysAvailable' => false,
                'mainLocale' => 'en',
                'availableLocales' => ['en', 'hr', 'de'],
                'parameters' => ['en' => [], 'hr' => [], 'de' => []],
            ]
        );

        $this->collectionMapper->mapQuery($persistenceQuery, ['fr', 'no']);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\CollectionMapper::mapQuery
     */
    public function testMapQueryWithInvalidType()
    {
        $persistenceQuery = new Query(
            [
                'id' => 1,
                'status' => Value::STATUS_PUBLISHED,
                'collectionId' => 42,
                'type' => 'unknown',
                'alwaysAvailable' => false,
                'isTranslatable' => true,
                'mainLocale' => 'en',
                'availableLocales' => ['en'],
                'parameters' => [
                    'en' => [
                        'param' => 'value',
                    ],
                ],
            ]
        );

        $query = $this->collectionMapper->mapQuery($persistenceQuery);

        $this->assertInstanceOf(NullQueryType::class, $query->getQueryType());

        $this->assertInstanceOf(APIQuery::class, $query);
        $this->assertEquals(1, $query->getId());
        $this->assertEquals(42, $query->getCollectionId());
        $this->assertEquals(Value::STATUS_PUBLISHED, $query->getStatus());
        $this->assertTrue($query->isPublished());
        $this->assertTrue($query->isTranslatable());
        $this->assertEquals('en', $query->getMainLocale());
        $this->assertFalse($query->isAlwaysAvailable());
        $this->assertEquals(['en'], $query->getAvailableLocales());

        $this->assertFalse($query->hasParameter('param'));
        $this->assertFalse($query->hasParameter('param2'));

        $this->assertEquals('en', $query->getLocale());
    }
}
