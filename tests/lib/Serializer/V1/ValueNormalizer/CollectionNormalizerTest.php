<?php

namespace Netgen\BlockManager\Tests\Serializer\V1\ValueNormalizer;

use Netgen\BlockManager\Core\Values\Collection\Collection;
use Netgen\BlockManager\Core\Values\Collection\Item;
use Netgen\BlockManager\Core\Values\Collection\Query;
use Netgen\BlockManager\Serializer\V1\ValueNormalizer\CollectionNormalizer;
use Netgen\BlockManager\Serializer\Values\VersionedValue;
use Netgen\BlockManager\Tests\Core\Stubs\Value;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;

class CollectionNormalizerTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $serializerMock;

    /**
     * @var \Netgen\BlockManager\Serializer\V1\ValueNormalizer\CollectionNormalizer
     */
    protected $normalizer;

    public function setUp()
    {
        $this->serializerMock = $this->createMock(Serializer::class);

        $this->normalizer = new CollectionNormalizer();
        $this->normalizer->setSerializer($this->serializerMock);
    }

    /**
     * @covers \Netgen\BlockManager\Serializer\V1\ValueNormalizer\CollectionNormalizer::normalize
     */
    public function testNormalize()
    {
        $collection = new Collection(
            array(
                'id' => 42,
                'type' => Collection::TYPE_DYNAMIC,
                'items' => array(
                    new Item(array('position' => 0, 'type' => Item::TYPE_MANUAL)),
                    new Item(array('position' => 1, 'type' => Item::TYPE_MANUAL)),
                ),
                'query' => new Query(),
            )
        );

        $this->serializerMock
            ->expects($this->at(0))
            ->method('normalize')
            ->with(
                $this->equalTo(
                    array(
                        new VersionedValue(new Item(array('position' => 0, 'type' => Item::TYPE_MANUAL)), 1),
                        new VersionedValue(new Item(array('position' => 1, 'type' => Item::TYPE_MANUAL)), 1),
                    )
                )
            )
            ->will($this->returnValue(array('items')));

        $this->serializerMock
            ->expects($this->at(1))
            ->method('normalize')
            ->with(
                $this->equalTo(
                    new VersionedValue(new Query(), 1)
                )
            )
            ->will($this->returnValue(array('query')));

        $this->assertEquals(
            array(
                'id' => $collection->getId(),
                'type' => $collection->getType(),
                'items' => array('items'),
                'query' => array('query'),
            ),
            $this->normalizer->normalize(new VersionedValue($collection, 1))
        );
    }

    /**
     * @param mixed $data
     * @param bool $expected
     *
     * @covers \Netgen\BlockManager\Serializer\V1\ValueNormalizer\CollectionNormalizer::supportsNormalization
     * @dataProvider supportsNormalizationProvider
     */
    public function testSupportsNormalization($data, $expected)
    {
        $this->assertEquals($expected, $this->normalizer->supportsNormalization($data));
    }

    /**
     * Provider for {@link self::testSupportsNormalization}.
     *
     * @return array
     */
    public function supportsNormalizationProvider()
    {
        return array(
            array(null, false),
            array(true, false),
            array(false, false),
            array('block', false),
            array(array(), false),
            array(42, false),
            array(42.12, false),
            array(new Value(), false),
            array(new Collection(), false),
            array(new VersionedValue(new Value(), 1), false),
            array(new VersionedValue(new Collection(), 2), false),
            array(new VersionedValue(new Collection(), 1), true),
        );
    }
}
