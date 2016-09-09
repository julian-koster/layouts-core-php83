<?php

namespace Netgen\BlockManager\Tests\Serializer\V1\ValueNormalizer;

use Netgen\BlockManager\API\Service\LayoutService;
use Netgen\BlockManager\Core\Values\Page\Block;
use Netgen\BlockManager\Core\Values\Page\Zone;
use Netgen\BlockManager\Core\Values\Page\Layout;
use Netgen\BlockManager\Serializer\V1\ValueNormalizer\LayoutNormalizer;
use Netgen\BlockManager\Serializer\Values\VersionedValue;
use Netgen\BlockManager\Configuration\LayoutType\LayoutType;
use Netgen\BlockManager\Configuration\LayoutType\Zone as LayoutTypeZone;
use Netgen\BlockManager\API\Values\Page\Zone as APIZone;
use Netgen\BlockManager\Tests\Core\Stubs\Value;
use DateTime;
use PHPUnit\Framework\TestCase;

class LayoutNormalizerTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $layoutServiceMock;

    /**
     * @var \Netgen\BlockManager\Serializer\V1\ValueNormalizer\LayoutNormalizer
     */
    protected $normalizer;

    public function setUp()
    {
        $this->layoutServiceMock = $this->createMock(LayoutService::class);

        $this->normalizer = new LayoutNormalizer(
            $this->layoutServiceMock
        );
    }

    /**
     * @covers \Netgen\BlockManager\Serializer\V1\ValueNormalizer\LayoutNormalizer::normalize
     * @covers \Netgen\BlockManager\Serializer\V1\ValueNormalizer\LayoutNormalizer::getZones
     */
    public function testNormalizeLayout()
    {
        $currentDate = new DateTime();
        $currentDate->setTimestamp(time());

        $block = new Block(
            array(
                'id' => 24,
            )
        );

        $layoutType = new LayoutType(
            '4_zones_a',
            true,
            '4 zones A',
            array(
                'left' => new LayoutTypeZone('left', 'Left', array('title')),
                'right' => new LayoutTypeZone('right', 'Right', array()),
            )
        );

        $layout = new Layout(
            array(
                'id' => 42,
                'layoutType' => $layoutType,
                'status' => Layout::STATUS_DRAFT,
                'created' => $currentDate,
                'modified' => $currentDate,
                'shared' => true,
                'zones' => array(
                    'left' => new Zone(
                        array(
                            'identifier' => 'left',
                            'linkedLayoutId' => null,
                            'linkedZoneIdentifier' => null,
                            'blocks' => array($block),
                        )
                    ),
                    'right' => new Zone(
                        array(
                            'identifier' => 'right',
                            'linkedLayoutId' => 24,
                            'linkedZoneIdentifier' => 'top',
                            'blocks' => array(),
                        )
                    ),
                ),
            )
        );

        $this->layoutServiceMock
            ->expects($this->once())
            ->method('isPublished')
            ->with($this->equalTo($layout))
            ->will($this->returnValue(true));

        $this->layoutServiceMock
            ->expects($this->any())
            ->method('findLinkedZone')
            ->with($this->isInstanceOf(APIZone::class))
            ->will(
                $this->returnCallback(function (APIZone $zone) {
                    return new Zone(
                        array(
                            'layoutId' => $zone->getLinkedLayoutId(),
                            'identifier' => $zone->getLinkedZoneIdentifier(),
                        )
                    );
                })
            );

        $this->assertEquals(
            array(
                'id' => $layout->getId(),
                'type' => $layoutType->getIdentifier(),
                'published' => false,
                'has_published_state' => true,
                'created_at' => $layout->getCreated()->format(DateTime::ISO8601),
                'updated_at' => $layout->getModified()->format(DateTime::ISO8601),
                'shared' => true,
                'name' => $layout->getName(),
                'zones' => array(
                    array(
                        'identifier' => 'left',
                        'name' => 'Left',
                        'block_ids' => array(24),
                        'allowed_block_definitions' => array('title'),
                        'linked_layout_id' => null,
                        'linked_zone_identifier' => null,
                    ),
                    array(
                        'identifier' => 'right',
                        'name' => 'Right',
                        'block_ids' => array(),
                        'allowed_block_definitions' => true,
                        'linked_layout_id' => 24,
                        'linked_zone_identifier' => 'top',
                    ),
                ),
            ),
            $this->normalizer->normalize(new VersionedValue($layout, 1))
        );
    }

    /**
     * @param mixed $data
     * @param bool $expected
     *
     * @covers \Netgen\BlockManager\Serializer\V1\ValueNormalizer\LayoutNormalizer::supportsNormalization
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
            array('layout', false),
            array(array(), false),
            array(42, false),
            array(42.12, false),
            array(new Value(), false),
            array(new Layout(), false),
            array(new VersionedValue(new Value(), 1), false),
            array(new VersionedValue(new Layout(), 2), false),
            array(new VersionedValue(new Layout(), 1), true),
        );
    }
}
