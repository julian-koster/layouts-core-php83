<?php

namespace Netgen\BlockManager\Tests\View\View;

use Netgen\BlockManager\Core\Values\Block\Block;
use Netgen\BlockManager\Core\Values\Block\Placeholder;
use Netgen\BlockManager\View\View\PlaceholderView;
use PHPUnit\Framework\TestCase;

final class PlaceholderViewTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\API\Values\Block\Placeholder
     */
    private $placeholder;

    /**
     * @var \Netgen\BlockManager\View\View\PlaceholderViewInterface
     */
    private $view;

    public function setUp()
    {
        $this->placeholder = new Placeholder(['identifier' => 'main']);

        $this->view = new PlaceholderView(
            [
                'placeholder' => $this->placeholder,
                'block' => new Block(['id' => 42]),
            ]
        );

        $this->view->addParameter('param', 'value');
        $this->view->addParameter('placeholder', 42);
        $this->view->addParameter('block', 42);
    }

    /**
     * @covers \Netgen\BlockManager\View\View\PlaceholderView::__construct
     * @covers \Netgen\BlockManager\View\View\PlaceholderView::getPlaceholder
     */
    public function testGetPlaceholder()
    {
        $this->assertEquals($this->placeholder, $this->view->getPlaceholder());
    }

    /**
     * @covers \Netgen\BlockManager\View\View\PlaceholderView::getBlock
     */
    public function testGetBlock()
    {
        $this->assertEquals(new Block(['id' => 42]), $this->view->getBlock());
    }

    /**
     * @covers \Netgen\BlockManager\View\View\PlaceholderView::getParameters
     */
    public function testGetParameters()
    {
        $this->assertEquals(
            [
                'param' => 'value',
                'placeholder' => $this->placeholder,
                'block' => new Block(['id' => 42]),
            ],
            $this->view->getParameters()
        );
    }

    /**
     * @covers \Netgen\BlockManager\View\View\PlaceholderView::getIdentifier
     */
    public function testGetIdentifier()
    {
        $this->assertEquals('placeholder_view', $this->view->getIdentifier());
    }

    /**
     * @covers \Netgen\BlockManager\View\View\PlaceholderView::jsonSerialize
     */
    public function testJsonSerialize()
    {
        $this->assertEquals(
            [
                'blockId' => 42,
                'placeholder' => 'main',
            ],
            $this->view->jsonSerialize()
        );
    }
}
