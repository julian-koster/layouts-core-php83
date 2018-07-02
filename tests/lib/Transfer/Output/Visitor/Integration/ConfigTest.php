<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Transfer\Output\Visitor\Integration;

use Netgen\BlockManager\API\Values\Config\Config as APIConfig;
use Netgen\BlockManager\Core\Values\Block\Block;
use Netgen\BlockManager\Core\Values\Config\Config as ConfigValue;
use Netgen\BlockManager\Core\Values\Layout\Layout;
use Netgen\BlockManager\Transfer\Output\Visitor\Config;
use Netgen\BlockManager\Transfer\Output\VisitorInterface;

abstract class ConfigTest extends VisitorTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->blockService = $this->createBlockService();
    }

    /**
     * @expectedException \Netgen\BlockManager\Exception\RuntimeException
     * @expectedExceptionMessage Implementation requires sub-visitor
     */
    public function testVisitThrowsRuntimeExceptionWithoutSubVisitor(): void
    {
        $this->getVisitor()->visit(new ConfigValue());
    }

    public function getVisitor(): VisitorInterface
    {
        return new Config();
    }

    public function acceptProvider(): array
    {
        return [
            [new ConfigValue(), true],
            [new Layout(), false],
            [new Block(), false],
        ];
    }

    public function visitProvider(): array
    {
        return [
            [function (): APIConfig { return $this->blockService->loadBlock(31)->getConfig('key'); }, 'config/block_31.json'],
        ];
    }
}
