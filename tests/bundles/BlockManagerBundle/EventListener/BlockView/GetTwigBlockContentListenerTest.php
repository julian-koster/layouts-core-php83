<?php

namespace Netgen\Bundle\BlockManagerBundle\Tests\EventListener\BlockView;

use Netgen\BlockManager\Block\BlockDefinition;
use Netgen\BlockManager\Block\TwigBlockDefinition;
use Netgen\BlockManager\Core\Values\Block\Block;
use Netgen\BlockManager\Event\BlockManagerEvents;
use Netgen\BlockManager\Event\CollectViewParametersEvent;
use Netgen\BlockManager\Tests\Block\Stubs\TwigBlockDefinitionHandler;
use Netgen\BlockManager\Tests\Core\Stubs\Value;
use Netgen\BlockManager\Tests\View\Stubs\View;
use Netgen\BlockManager\View\Twig\ContextualizedTwigTemplate;
use Netgen\BlockManager\View\View\BlockView;
use Netgen\Bundle\BlockManagerBundle\EventListener\BlockView\GetTwigBlockContentListener;
use PHPUnit\Framework\TestCase;
use stdClass;
use Twig\Template;

final class GetTwigBlockContentListenerTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\BlockManagerBundle\EventListener\BlockView\GetTwigBlockContentListener
     */
    private $listener;

    public function setUp()
    {
        $this->listener = new GetTwigBlockContentListener();
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\BlockView\GetTwigBlockContentListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents()
    {
        $this->assertEquals(
            [BlockManagerEvents::RENDER_VIEW => 'onRenderView'],
            $this->listener->getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\BlockView\GetTwigBlockContentListener::getTwigBlockContent
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\BlockView\GetTwigBlockContentListener::onRenderView
     */
    public function testOnRenderView()
    {
        $block = new Block(
            [
                'id' => 42,
                'definition' => new TwigBlockDefinition(
                    [
                        'handler' => new TwigBlockDefinitionHandler(),
                    ]
                ),
            ]
        );

        $blockView = new BlockView(['block' => $block]);

        $twigTemplateMock = $this->createMock(Template::class);

        $twigTemplateMock
            ->expects($this->once())
            ->method('hasBlock')
            ->will($this->returnValue(true));

        $twigTemplateMock
            ->expects($this->once())
            ->method('displayBlock')
            ->will($this->returnCallback(function () { echo 'rendered twig block'; }));

        $blockView->addParameter('twig_template', new ContextualizedTwigTemplate($twigTemplateMock));

        $event = new CollectViewParametersEvent($blockView);
        $this->listener->onRenderView($event);

        $this->assertArrayHasKey('twig_content', $event->getParameters());
        $this->assertEquals('rendered twig block', $event->getParameters()['twig_content']);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\BlockView\GetTwigBlockContentListener::onRenderView
     */
    public function testOnRenderViewWithNoTwigBlock()
    {
        $block = new Block(
            [
                'id' => 42,
                'definition' => new BlockDefinition(),
            ]
        );

        $blockView = new BlockView(['block' => $block]);
        $event = new CollectViewParametersEvent($blockView);
        $this->listener->onRenderView($event);

        $this->assertEquals([], $event->getParameters());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\BlockView\GetTwigBlockContentListener::getTwigBlockContent
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\BlockView\GetTwigBlockContentListener::onRenderView
     */
    public function testOnRenderViewInvalidTwigTemplate()
    {
        $block = new Block(
            [
                'id' => 42,
                'definition' => new TwigBlockDefinition(),
            ]
        );

        $blockView = new BlockView(['block' => $block]);
        $blockView->addParameter('twig_template', new stdClass());

        $event = new CollectViewParametersEvent($blockView);
        $this->listener->onRenderView($event);

        $this->assertArrayHasKey('twig_content', $event->getParameters());
        $this->assertEquals('', $event->getParameters()['twig_content']);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\BlockView\GetTwigBlockContentListener::getTwigBlockContent
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\BlockView\GetTwigBlockContentListener::onRenderView
     */
    public function testOnRenderViewWithNoTwigTemplate()
    {
        $block = new Block(
            [
                'id' => 42,
                'definition' => new TwigBlockDefinition(),
            ]
        );

        $blockView = new BlockView(['block' => $block]);

        $event = new CollectViewParametersEvent($blockView);
        $this->listener->onRenderView($event);

        $this->assertArrayHasKey('twig_content', $event->getParameters());
        $this->assertEquals('', $event->getParameters()['twig_content']);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\BlockView\GetTwigBlockContentListener::onRenderView
     */
    public function testOnRenderViewWithNoBlockView()
    {
        $view = new View(['value' => new Value()]);
        $event = new CollectViewParametersEvent($view);
        $this->listener->onRenderView($event);

        $this->assertEquals([], $event->getParameters());
    }
}
