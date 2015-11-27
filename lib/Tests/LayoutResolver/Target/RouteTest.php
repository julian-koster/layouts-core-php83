<?php

namespace Netgen\BlockManager\Tests\LayoutResolver\Target;

use Netgen\BlockManager\LayoutResolver\Target\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Netgen\BlockManager\LayoutResolver\Target\Route::getIdentifier
     */
    public function testGetIdentifier()
    {
        $target = new Route();
        self::assertEquals('route', $target->getIdentifier());
    }
}
