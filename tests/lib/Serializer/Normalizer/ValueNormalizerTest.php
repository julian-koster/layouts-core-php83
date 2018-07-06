<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Serializer\Normalizer;

use Netgen\BlockManager\Core\Values\Block\Block;
use Netgen\BlockManager\Serializer\Normalizer\ValueNormalizer;
use Netgen\BlockManager\Serializer\Values\Value;
use Netgen\BlockManager\Serializer\Values\VersionedValue;
use Netgen\BlockManager\Tests\Core\Stubs\Value as StubValue;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ValueNormalizerTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $normalizerMock;

    /**
     * @var \Netgen\BlockManager\Serializer\Normalizer\ValueNormalizer
     */
    private $normalizer;

    public function setUp(): void
    {
        $this->normalizerMock = $this->createMock(NormalizerInterface::class);

        $this->normalizer = new ValueNormalizer();
        $this->normalizer->setNormalizer($this->normalizerMock);
    }

    /**
     * @covers \Netgen\BlockManager\Serializer\Normalizer::setNormalizer
     * @covers \Netgen\BlockManager\Serializer\Normalizer\ValueNormalizer::normalize
     */
    public function testNormalize(): void
    {
        $value = new StubValue();
        $this->normalizerMock
            ->expects($this->at(0))
            ->method('normalize')
            ->with(
                $this->identicalTo($value),
                $this->identicalTo('json'),
                $this->identicalTo(['context'])
            )
            ->will($this->returnValue(['serialized']));

        $data = $this->normalizer->normalize(new Value($value), 'json', ['context']);

        $this->assertSame(['serialized'], $data);
    }

    /**
     * @param mixed $data
     * @param bool $expected
     *
     * @covers \Netgen\BlockManager\Serializer\Normalizer\ValueNormalizer::supportsNormalization
     * @dataProvider supportsNormalizationProvider
     */
    public function testSupportsNormalization($data, bool $expected): void
    {
        $this->assertSame($expected, $this->normalizer->supportsNormalization($data));
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
            [new StubValue(), false],
            [new Block(), false],
            [new VersionedValue(new Block(), 1), false],
            [new Value([new Block()], 1), true],
        ];
    }
}
