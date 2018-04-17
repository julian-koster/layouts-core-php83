<?php

namespace Netgen\BlockManager\Tests\Serializer\Normalizer\V1;

use Doctrine\Common\Collections\ArrayCollection;
use Netgen\BlockManager\Core\Values\Block\Block;
use Netgen\BlockManager\Core\Values\Block\Placeholder;
use Netgen\BlockManager\Serializer\Normalizer\V1\PlaceholderNormalizer;
use Netgen\BlockManager\Serializer\Values\VersionedValue;
use Netgen\BlockManager\Serializer\Values\View;
use Netgen\BlockManager\Tests\Core\Stubs\Value;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;

final class PlaceholderNormalizerTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $serializerMock;

    /**
     * @var \Netgen\BlockManager\Serializer\Normalizer\V1\PlaceholderNormalizer
     */
    private $normalizer;

    public function setUp()
    {
        $this->serializerMock = $this->createMock(Serializer::class);

        $this->normalizer = new PlaceholderNormalizer();
        $this->normalizer->setSerializer($this->serializerMock);
    }

    /**
     * @covers \Netgen\BlockManager\Serializer\Normalizer\V1\PlaceholderNormalizer::normalize
     */
    public function testNormalize()
    {
        $placeholder = new Placeholder(
            [
                'identifier' => 'main',
                'blocks' => new ArrayCollection([new Block()]),
            ]
        );

        $this->serializerMock
            ->expects($this->at(0))
            ->method('normalize')
            ->with($this->equalTo([new View(new Block(), 1)]))
            ->will($this->returnValue(['normalized blocks']));

        $this->assertEquals(
            [
                'identifier' => 'main',
                'blocks' => ['normalized blocks'],
            ],
            $this->normalizer->normalize(new VersionedValue($placeholder, 1))
        );
    }

    /**
     * @param mixed $data
     * @param bool $expected
     *
     * @covers \Netgen\BlockManager\Serializer\Normalizer\V1\PlaceholderNormalizer::supportsNormalization
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
        return [
            [null, false],
            [true, false],
            [false, false],
            ['placeholder', false],
            [[], false],
            [42, false],
            [42.12, false],
            [new Value(), false],
            [new Placeholder(), false],
            [new VersionedValue(new Value(), 1), false],
            [new VersionedValue(new Placeholder(), 2), false],
            [new VersionedValue(new Placeholder(), 1), true],
        ];
    }
}
