<?php

namespace Netgen\BlockManager\Tests\View\Matcher\Item;

use Netgen\BlockManager\Item\Item;
use Netgen\BlockManager\Tests\Core\Stubs\Value;
use Netgen\BlockManager\Tests\View\Stubs\View;
use Netgen\BlockManager\View\Matcher\Item\ValueType;
use Netgen\BlockManager\View\View\ItemView;
use PHPUnit\Framework\TestCase;

final class ValueTypeTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\View\Matcher\MatcherInterface
     */
    private $matcher;

    public function setUp()
    {
        $this->matcher = new ValueType();
    }

    /**
     * @param array $config
     * @param bool $expected
     *
     * @covers \Netgen\BlockManager\View\Matcher\Item\ValueType::match
     * @dataProvider matchProvider
     */
    public function testMatch(array $config, $expected)
    {
        $view = new ItemView(['item' => new Item(['valueType' => 'value'])]);

        $this->assertEquals($expected, $this->matcher->match($view, $config));
    }

    /**
     * Provider for {@link self::testMatch}.
     *
     * @return array
     */
    public function matchProvider()
    {
        return [
            [[], false],
            [['other'], false],
            [['value'], true],
            [['other1', 'other2'], false],
            [['other', 'value'], true],
        ];
    }

    /**
     * @covers \Netgen\BlockManager\View\Matcher\Item\ValueType::match
     */
    public function testMatchWithNoItemView()
    {
        $this->assertFalse($this->matcher->match(new View(['value' => new Value()]), []));
    }
}
