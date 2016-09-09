<?php

namespace Netgen\Bundle\BlockManagerBundle\Tests\EventListener;

use Netgen\BlockManager\Configuration\ConfigurationInterface;
use Netgen\BlockManager\Core\Values\Page\Layout;
use Netgen\BlockManager\Layout\Resolver\LayoutResolverInterface;
use Netgen\BlockManager\Core\Values\LayoutResolver\Rule;
use Netgen\BlockManager\View\View\LayoutView;
use Netgen\BlockManager\View\ViewBuilderInterface;
use Netgen\Bundle\BlockManagerBundle\EventListener\LayoutResolverListener;
use Netgen\Bundle\BlockManagerBundle\EventListener\SetIsApiRequestListener;
use Netgen\Bundle\BlockManagerBundle\Templating\Twig\GlobalVariable;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

class LayoutResolverListenerTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $layoutResolverMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $viewBuilderMock;

    /**
     * @var \Netgen\Bundle\BlockManagerBundle\Templating\Twig\GlobalVariable
     */
    protected $globalVariable;

    /**
     * @var \Netgen\Bundle\BlockManagerBundle\EventListener\LayoutResolverListener
     */
    protected $listener;

    /**
     * Sets up the test.
     */
    public function setUp()
    {
        $this->layoutResolverMock = $this->createMock(
            LayoutResolverInterface::class
        );

        $this->viewBuilderMock = $this->createMock(
            ViewBuilderInterface::class
        );

        $this->globalVariable = new GlobalVariable(
            $this->createMock(ConfigurationInterface::class)
        );

        $this->listener = new LayoutResolverListener(
            $this->layoutResolverMock,
            $this->viewBuilderMock,
            $this->globalVariable
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\LayoutResolverListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents()
    {
        $this->assertEquals(
            array(KernelEvents::REQUEST => array('onKernelRequest', -255)),
            $this->listener->getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\LayoutResolverListener::__construct
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\LayoutResolverListener::onKernelRequest
     */
    public function testOnKernelRequest()
    {
        $layout = new Layout();
        $layoutView = new LayoutView($layout);

        $this->layoutResolverMock
            ->expects($this->once())
            ->method('resolveRules')
            ->will(
                $this->returnValue(
                    array(
                        new Rule(
                            array(
                                'layout' => $layout,
                            )
                        ),
                    )
                )
            );

        $this->viewBuilderMock
            ->expects($this->once())
            ->method('buildView')
            ->with($this->equalTo($layout))
            ->will($this->returnValue($layoutView));

        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $event = new GetResponseEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        $this->listener->onKernelRequest($event);

        $this->assertEquals($layoutView, $this->globalVariable->getLayoutView());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\LayoutResolverListener::onKernelRequest
     */
    public function testOnKernelRequestWithNoRulesResolved()
    {
        $this->layoutResolverMock
            ->expects($this->once())
            ->method('resolveRules')
            ->will($this->returnValue(array()));

        $this->viewBuilderMock
            ->expects($this->never())
            ->method('buildView');

        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $event = new GetResponseEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        $this->listener->onKernelRequest($event);

        $this->assertNull($this->globalVariable->getLayoutView());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\LayoutResolverListener::onKernelRequest
     */
    public function testOnKernelRequestWithNonExistingLayout()
    {
        $this->layoutResolverMock
            ->expects($this->once())
            ->method('resolveRules')
            ->will($this->returnValue(array(new Rule(array('layout' => null)))));

        $this->viewBuilderMock
            ->expects($this->never())
            ->method('buildView');

        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $event = new GetResponseEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        $this->listener->onKernelRequest($event);

        $this->assertNull($this->globalVariable->getLayoutView());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\LayoutResolverListener::onKernelRequest
     */
    public function testOnKernelRequestInSubRequest()
    {
        $this->layoutResolverMock
            ->expects($this->never())
            ->method('resolveRules');
        $this->viewBuilderMock
            ->expects($this->never())
            ->method('buildView');

        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $event = new GetResponseEvent($kernelMock, $request, HttpKernelInterface::SUB_REQUEST);
        $this->listener->onKernelRequest($event);

        $this->assertNull($this->globalVariable->getLayoutView());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\EventListener\LayoutResolverListener::onKernelRequest
     */
    public function testOnKernelRequestInApiRequest()
    {
        $this->layoutResolverMock
            ->expects($this->never())
            ->method('resolveRules');

        $this->viewBuilderMock
            ->expects($this->never())
            ->method('buildView');

        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');
        $request->attributes->set(SetIsApiRequestListener::API_FLAG_NAME, true);

        $event = new GetResponseEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        $this->listener->onKernelRequest($event);

        $this->assertNull($this->globalVariable->getLayoutView());
    }
}
