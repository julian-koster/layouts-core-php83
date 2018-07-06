<?php

declare(strict_types=1);

namespace Netgen\Bundle\BlockManagerBundle\Tests\Templating\Twig\Runtime;

use Exception;
use Netgen\BlockManager\API\Service\BlockService;
use Netgen\BlockManager\Block\BlockDefinition;
use Netgen\BlockManager\Core\Values\Block\Block;
use Netgen\BlockManager\Core\Values\Block\Placeholder;
use Netgen\BlockManager\Core\Values\LayoutResolver\Condition;
use Netgen\BlockManager\Item\CmsItem;
use Netgen\BlockManager\Locale\LocaleProviderInterface;
use Netgen\BlockManager\Tests\Stubs\ErrorHandler;
use Netgen\BlockManager\View\RendererInterface;
use Netgen\BlockManager\View\Twig\ContextualizedTwigTemplate;
use Netgen\BlockManager\View\ViewInterface;
use Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Template;

final class RenderingRuntimeTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $blockServiceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $rendererMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $localeProviderMock;

    /**
     * @var \Netgen\BlockManager\Tests\Stubs\ErrorHandler
     */
    private $errorHandler;

    /**
     * @var \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime
     */
    private $runtime;

    public function setUp(): void
    {
        $this->blockServiceMock = $this->createMock(BlockService::class);
        $this->rendererMock = $this->createMock(RendererInterface::class);
        $this->localeProviderMock = $this->createMock(LocaleProviderInterface::class);
        $this->errorHandler = new ErrorHandler();

        $this->runtime = new RenderingRuntime(
            $this->blockServiceMock,
            $this->rendererMock,
            $this->localeProviderMock,
            new RequestStack(),
            $this->errorHandler
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::__construct
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderBlock
     */
    public function testRenderBlock(): void
    {
        $block = new Block();
        $twigTemplate = new ContextualizedTwigTemplate($this->createMock(Template::class));

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($block),
                $this->identicalTo(ViewInterface::CONTEXT_DEFAULT),
                $this->identicalTo(
                    [
                        'twig_template' => $twigTemplate,
                        'param' => 'value',
                    ]
                )
            )
            ->will($this->returnValue('rendered block'));

        $this->assertSame(
            'rendered block',
            $this->runtime->renderBlock(
                [
                    'twig_template' => $twigTemplate,
                ],
                $block,
                ['param' => 'value']
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderBlock
     */
    public function testRenderBlockWithoutTwigTemplate(): void
    {
        $block = new Block();

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($block),
                $this->identicalTo(ViewInterface::CONTEXT_DEFAULT),
                $this->identicalTo(
                    [
                        'twig_template' => null,
                        'param' => 'value',
                    ]
                )
            )
            ->will($this->returnValue('rendered block'));

        $this->assertSame(
            'rendered block',
            $this->runtime->renderBlock(
                [],
                $block,
                ['param' => 'value']
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderBlock
     */
    public function testRenderBlockWithViewContext(): void
    {
        $block = new Block();
        $twigTemplate = new ContextualizedTwigTemplate($this->createMock(Template::class));

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($block),
                $this->identicalTo(ViewInterface::CONTEXT_API),
                $this->identicalTo(
                    [
                        'twig_template' => $twigTemplate,
                        'param' => 'value',
                    ]
                )
            )
            ->will($this->returnValue('rendered block'));

        $this->assertSame(
            'rendered block',
            $this->runtime->renderBlock(
                [
                    'twig_template' => $twigTemplate,
                ],
                $block,
                ['param' => 'value'],
                ViewInterface::CONTEXT_API
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderBlock
     */
    public function testRenderBlockWithViewContextFromTwigContext(): void
    {
        $block = new Block();
        $twigTemplate = new ContextualizedTwigTemplate($this->createMock(Template::class));

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($block),
                $this->identicalTo(ViewInterface::CONTEXT_API),
                $this->identicalTo(
                    [
                        'twig_template' => $twigTemplate,
                        'param' => 'value',
                    ]
                )
            )
            ->will($this->returnValue('rendered block'));

        $this->assertSame(
            'rendered block',
            $this->runtime->renderBlock(
                [
                    'view_context' => ViewInterface::CONTEXT_API,
                    'twig_template' => $twigTemplate,
                ],
                $block,
                ['param' => 'value']
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderBlock
     */
    public function testRenderBlockReturnsEmptyStringOnException(): void
    {
        $block = new Block(['definition' => new BlockDefinition()]);

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->will($this->throwException(new Exception()));

        $renderedBlock = $this->runtime->renderBlock(
            [
                'twig_template' => new ContextualizedTwigTemplate(
                    $this->createMock(Template::class)
                ),
            ],
            $block
        );

        $this->assertSame('', $renderedBlock);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderBlock
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testRenderBlockThrowsExceptionInDebug(): void
    {
        $this->errorHandler->setThrow(true);
        $block = new Block(['definition' => new BlockDefinition()]);

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->runtime->renderBlock(
            [
                'twig_template' => new ContextualizedTwigTemplate(
                    $this->createMock(Template::class)
                ),
            ],
            $block
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderPlaceholder
     */
    public function testRenderPlaceholder(): void
    {
        $placeholder = new Placeholder();
        $block = new Block(
            [
                'placeholders' => [
                    'main' => $placeholder,
                ],
            ]
        );

        $twigTemplate = new ContextualizedTwigTemplate($this->createMock(Template::class));

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($placeholder),
                $this->identicalTo(ViewInterface::CONTEXT_DEFAULT),
                $this->identicalTo(
                    [
                        'block' => $block,
                        'twig_template' => $twigTemplate,
                        'param' => 'value',
                    ]
                )
            )
            ->will($this->returnValue('rendered placeholder'));

        $this->assertSame(
            'rendered placeholder',
            $this->runtime->renderPlaceholder(
                [
                    'twig_template' => $twigTemplate,
                ],
                $block,
                'main',
                [
                    'block' => $block,
                    'param' => 'value',
                ]
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderPlaceholder
     */
    public function testRenderPlaceholderWithoutTwigTemplate(): void
    {
        $placeholder = new Placeholder();
        $block = new Block(
            [
                'placeholders' => [
                    'main' => $placeholder,
                ],
            ]
        );

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($placeholder),
                $this->identicalTo(ViewInterface::CONTEXT_DEFAULT),
                $this->identicalTo(
                    [
                        'block' => $block,
                        'twig_template' => null,
                        'param' => 'value',
                    ]
                )
            )
            ->will($this->returnValue('rendered placeholder'));

        $this->assertSame(
            'rendered placeholder',
            $this->runtime->renderPlaceholder(
                [],
                $block,
                'main',
                [
                    'block' => $block,
                    'param' => 'value',
                ]
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderPlaceholder
     */
    public function testRenderPlaceholderWithViewContext(): void
    {
        $placeholder = new Placeholder();
        $block = new Block(
            [
                'placeholders' => [
                    'main' => $placeholder,
                ],
            ]
        );

        $twigTemplate = new ContextualizedTwigTemplate($this->createMock(Template::class));

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($placeholder),
                $this->identicalTo(ViewInterface::CONTEXT_API),
                $this->identicalTo(
                    [
                        'block' => $block,
                        'twig_template' => $twigTemplate,
                        'param' => 'value',
                    ]
                )
            )
            ->will($this->returnValue('rendered placeholder'));

        $this->assertSame(
            'rendered placeholder',
            $this->runtime->renderPlaceholder(
                [
                    'twig_template' => $twigTemplate,
                ],
                $block,
                'main',
                [
                    'block' => $block,
                    'param' => 'value',
                ],
                ViewInterface::CONTEXT_API
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderPlaceholder
     */
    public function testRenderPlaceholderWithViewContextFromTwigContext(): void
    {
        $placeholder = new Placeholder();
        $block = new Block(
            [
                'placeholders' => [
                    'main' => $placeholder,
                ],
            ]
        );

        $twigTemplate = new ContextualizedTwigTemplate($this->createMock(Template::class));

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($placeholder),
                $this->identicalTo(ViewInterface::CONTEXT_API),
                $this->identicalTo(
                    [
                        'block' => $block,
                        'twig_template' => $twigTemplate,
                        'param' => 'value',
                    ]
                )
            )
            ->will($this->returnValue('rendered placeholder'));

        $this->assertSame(
            'rendered placeholder',
            $this->runtime->renderPlaceholder(
                [
                    'view_context' => ViewInterface::CONTEXT_API,
                    'twig_template' => $twigTemplate,
                ],
                $block,
                'main',
                [
                    'block' => $block,
                    'param' => 'value',
                ]
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderPlaceholder
     */
    public function testRenderPlaceholderReturnsEmptyStringOnException(): void
    {
        $block = new Block(['placeholders' => ['main' => new Placeholder()]]);

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->will($this->throwException(new Exception()));

        $renderedBlock = $this->runtime->renderPlaceholder(
            [
                'twig_template' => new ContextualizedTwigTemplate(
                    $this->createMock(Template::class)
                ),
            ],
            $block,
            'main'
        );

        $this->assertSame('', $renderedBlock);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderPlaceholder
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testRenderPlaceholderThrowsExceptionInDebug(): void
    {
        $this->errorHandler->setThrow(true);
        $block = new Block(['placeholders' => ['main' => new Placeholder()]]);

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->will($this->throwException(new Exception('Test exception text')));

        $this->runtime->renderPlaceholder(
            [
                'twig_template' => new ContextualizedTwigTemplate(
                    $this->createMock(Template::class)
                ),
            ],
            $block,
            'main'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderItem
     */
    public function testRenderItem(): void
    {
        $cmsItem = new CmsItem();

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($cmsItem),
                $this->identicalTo(ViewInterface::CONTEXT_DEFAULT),
                $this->identicalTo(['view_type' => 'view_type', 'param' => 'value'])
            )
            ->will($this->returnValue('rendered item'));

        $this->assertSame(
            'rendered item',
            $this->runtime->renderItem(
                [],
                $cmsItem,
                'view_type',
                ['param' => 'value']
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderItem
     */
    public function testRenderItemWithViewContext(): void
    {
        $cmsItem = new CmsItem();

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($cmsItem),
                $this->identicalTo(ViewInterface::CONTEXT_API),
                $this->identicalTo(['view_type' => 'view_type', 'param' => 'value'])
            )
            ->will($this->returnValue('rendered item'));

        $this->assertSame(
            'rendered item',
            $this->runtime->renderItem(
                [],
                $cmsItem,
                'view_type',
                ['param' => 'value'],
                ViewInterface::CONTEXT_API
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderItem
     */
    public function testRenderItemWithViewContextFromTwigContext(): void
    {
        $cmsItem = new CmsItem();

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($cmsItem),
                $this->identicalTo(ViewInterface::CONTEXT_API),
                $this->identicalTo(['view_type' => 'view_type', 'param' => 'value'])
            )
            ->will($this->returnValue('rendered item'));

        $this->assertSame(
            'rendered item',
            $this->runtime->renderItem(
                [
                    'view_context' => ViewInterface::CONTEXT_API,
                ],
                $cmsItem,
                'view_type',
                ['param' => 'value']
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderItem
     */
    public function testRenderItemReturnsEmptyStringOnException(): void
    {
        $cmsItem = new CmsItem(['valueType' => 'value_type']);

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($cmsItem),
                $this->identicalTo(ViewInterface::CONTEXT_DEFAULT),
                $this->identicalTo(['view_type' => 'view_type', 'param' => 'value'])
            )
            ->will($this->throwException(new Exception()));

        $this->assertSame(
            '',
            $this->runtime->renderItem(
                [],
                $cmsItem,
                'view_type',
                ['param' => 'value']
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderItem
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testRenderItemThrowsExceptionInDebug(): void
    {
        $this->errorHandler->setThrow(true);

        $cmsItem = new CmsItem(['valueType' => 'value_type']);

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($cmsItem),
                $this->identicalTo(ViewInterface::CONTEXT_DEFAULT),
                $this->identicalTo(['view_type' => 'view_type', 'param' => 'value'])
            )
            ->will($this->throwException(new Exception('Test exception text')));

        $this->runtime->renderItem(
            [],
            $cmsItem,
            'view_type',
            ['param' => 'value']
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderValue
     */
    public function testRenderValue(): void
    {
        $condition = new Condition();

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($condition),
                $this->identicalTo(ViewInterface::CONTEXT_DEFAULT),
                $this->identicalTo(['param' => 'value'])
            )
            ->will($this->returnValue('rendered value'));

        $this->assertSame(
            'rendered value',
            $this->runtime->renderValue(
                [],
                $condition,
                ['param' => 'value']
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderValue
     */
    public function testRenderValueWithViewContext(): void
    {
        $condition = new Condition();

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($condition),
                $this->identicalTo(ViewInterface::CONTEXT_API),
                $this->identicalTo(['param' => 'value'])
            )
            ->will($this->returnValue('rendered value'));

        $this->assertSame(
            'rendered value',
            $this->runtime->renderValue(
                [],
                $condition,
                ['param' => 'value'],
                ViewInterface::CONTEXT_API
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderValue
     */
    public function testRenderValueWithContextFromTwigContext(): void
    {
        $condition = new Condition();

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($condition),
                $this->identicalTo(ViewInterface::CONTEXT_API),
                $this->identicalTo(['param' => 'value'])
            )
            ->will($this->returnValue('rendered value'));

        $this->assertSame(
            'rendered value',
            $this->runtime->renderValue(
                [
                    'view_context' => ViewInterface::CONTEXT_API,
                ],
                $condition,
                ['param' => 'value']
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderValue
     */
    public function testRenderValueReturnsEmptyStringOnException(): void
    {
        $condition = new Condition();

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($condition),
                $this->identicalTo(ViewInterface::CONTEXT_DEFAULT),
                $this->identicalTo(['param' => 'value'])
            )
            ->will($this->throwException(new Exception()));

        $this->assertSame(
            '',
            $this->runtime->renderValue(
                [],
                $condition,
                ['param' => 'value']
            )
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::getViewContext
     * @covers \Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\RenderingRuntime::renderValue
     * @expectedException \Exception
     * @expectedExceptionMessage Test exception text
     */
    public function testRenderValueThrowsExceptionInDebug(): void
    {
        $this->errorHandler->setThrow(true);

        $condition = new Condition();

        $this->rendererMock
            ->expects($this->once())
            ->method('renderValue')
            ->with(
                $this->identicalTo($condition),
                $this->identicalTo(ViewInterface::CONTEXT_DEFAULT),
                $this->identicalTo(['param' => 'value'])
            )
            ->will($this->throwException(new Exception('Test exception text')));

        $this->runtime->renderValue(
            [],
            $condition,
            ['param' => 'value']
        );
    }
}
