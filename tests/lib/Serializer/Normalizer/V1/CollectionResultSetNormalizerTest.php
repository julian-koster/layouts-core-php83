<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Serializer\Normalizer\V1;

use Doctrine\Common\Collections\ArrayCollection;
use Netgen\BlockManager\Collection\Result\ManualItem;
use Netgen\BlockManager\Collection\Result\Result;
use Netgen\BlockManager\Collection\Result\ResultSet;
use Netgen\BlockManager\Core\Values\Collection\Collection;
use Netgen\BlockManager\Core\Values\Collection\Item;
use Netgen\BlockManager\Serializer\Normalizer\V1\CollectionResultSetNormalizer;
use Netgen\BlockManager\Serializer\Values\VersionedValue;
use Netgen\BlockManager\Tests\Core\Stubs\Value;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class CollectionResultSetNormalizerTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $normalizerMock;

    /**
     * @var \Netgen\BlockManager\Serializer\Normalizer\V1\CollectionResultSetNormalizer
     */
    private $normalizer;

    public function setUp(): void
    {
        $this->normalizerMock = $this->createMock(NormalizerInterface::class);

        $this->normalizer = new CollectionResultSetNormalizer();
        $this->normalizer->setNormalizer($this->normalizerMock);
    }

    /**
     * @covers \Netgen\BlockManager\Serializer\Normalizer::setNormalizer
     * @covers \Netgen\BlockManager\Serializer\Normalizer\V1\CollectionResultSetNormalizer::getOverflowItems
     * @covers \Netgen\BlockManager\Serializer\Normalizer\V1\CollectionResultSetNormalizer::normalize
     */
    public function testNormalize(): void
    {
        $item1 = Item::fromArray(['position' => 0]);
        $item2 = Item::fromArray(['position' => 1]);
        $item3 = Item::fromArray(['position' => 2]);
        $item4 = Item::fromArray(['position' => 3]);

        $result1 = new Result(1, new ManualItem($item2));
        $result2 = new Result(2, new ManualItem($item3));

        $result = ResultSet::fromArray(
            [
                'collection' => Collection::fromArray(
                    [
                        'items' => new ArrayCollection([$item1, $item2, $item3, $item4]),
                    ]
                ),
                'results' => [$result1, $result2],
            ]
        );

        $this->normalizerMock
            ->expects(self::at(0))
            ->method('normalize')
            ->with(
                self::equalTo(
                    [
                        new VersionedValue($result1, 1),
                        new VersionedValue($result2, 1),
                    ]
                )
            )
            ->will(self::returnValue(['items']));

        $this->normalizerMock
            ->expects(self::at(1))
            ->method('normalize')
            ->with(
                self::equalTo(
                    [
                        new VersionedValue($item1, 1),
                        new VersionedValue($item4, 1),
                    ]
                )
            )
            ->will(self::returnValue(['overflow_items']));

        self::assertSame(
            [
                'items' => ['items'],
                'overflow_items' => ['overflow_items'],
            ],
            $this->normalizer->normalize(new VersionedValue($result, 1))
        );
    }

    /**
     * @param mixed $data
     * @param bool $expected
     *
     * @covers \Netgen\BlockManager\Serializer\Normalizer\V1\CollectionResultSetNormalizer::supportsNormalization
     * @dataProvider supportsNormalizationProvider
     */
    public function testSupportsNormalization($data, bool $expected): void
    {
        self::assertSame($expected, $this->normalizer->supportsNormalization($data));
    }

    public function supportsNormalizationProvider(): array
    {
        return [
            [null, false],
            [true, false],
            [false, false],
            ['block', false],
            [[], false],
            [42, false],
            [42.12, false],
            [new Value(), false],
            [new ResultSet(), false],
            [new VersionedValue(new Value(), 1), false],
            [new VersionedValue(new ResultSet(), 2), false],
            [new VersionedValue(new ResultSet(), 1), true],
        ];
    }
}
