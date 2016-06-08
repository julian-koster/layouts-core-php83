<?php

namespace Netgen\BlockManager\Tests\Serializer\Normalizer;

use Netgen\BlockManager\Core\Values\Page\Block;
use Netgen\BlockManager\Serializer\Normalizer\FormViewNormalizer;
use Netgen\BlockManager\Serializer\Values\VersionedValue;
use Netgen\BlockManager\Serializer\Values\FormView;
use Netgen\BlockManager\Tests\Core\Stubs\Value;
use Netgen\BlockManager\View\ViewInterface;
use Netgen\BlockManager\View\RendererInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView as SymfonyFormView;
use Symfony\Component\Serializer\Serializer;

class FormViewNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $viewRendererMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $serializerMock;

    /**
     * @var \Netgen\BlockManager\Serializer\Normalizer\FormViewNormalizer
     */
    protected $normalizer;

    public function setUp()
    {
        $this->viewRendererMock = $this->createMock(RendererInterface::class);
        $this->serializerMock = $this->createMock(Serializer::class);

        $this->normalizer = new FormViewNormalizer($this->viewRendererMock);
        $this->normalizer->setSerializer($this->serializerMock);
    }

    /**
     * @covers \Netgen\BlockManager\Serializer\Normalizer\FormViewNormalizer::__construct
     * @covers \Netgen\BlockManager\Serializer\Normalizer\FormViewNormalizer::normalize
     */
    public function testNormalize()
    {
        $form = $this->createMock(FormInterface::class);
        $form->expects($this->once())
            ->method('createView')
            ->will($this->returnValue(new SymfonyFormView()));

        $this->serializerMock
            ->expects($this->once())
            ->method('normalize')
            ->with($this->equalTo(new VersionedValue(new Value(), 1)))
            ->will($this->returnValue(array('id' => 42)));

        $this->viewRendererMock
            ->expects($this->once())
            ->method('renderValueObject')
            ->with(
                $this->equalTo(new Value()),
                $this->equalTo(ViewInterface::CONTEXT_API_FORM),
                $this->equalTo(
                    array(
                        'form' => new SymfonyFormView(),
                        'form_name' => 'full',
                        'api_version' => 1,
                    )
                )
            )
            ->will($this->returnValue('rendered form view'));

        $data = $this->normalizer->normalize(new FormView($form, 'full', new Value(), 1));

        self::assertEquals(array('id' => 42, 'form' => 'rendered form view'), $data);
    }

    /**
     * @param mixed $data
     * @param bool $expected
     *
     * @covers \Netgen\BlockManager\Serializer\Normalizer\FormViewNormalizer::supportsNormalization
     * @dataProvider supportsNormalizationProvider
     */
    public function testSupportsNormalization($data, $expected)
    {
        self::assertEquals($expected, $this->normalizer->supportsNormalization($data));
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
            array('block', false),
            array(array(), false),
            array(42, false),
            array(42.12, false),
            array(new Value(), false),
            array(new Block(), false),
            array(new VersionedValue(new Block(), 1), false),
            array(new FormView($this->createMock(FormInterface::class), 'full', new Block(), 1), true),
        );
    }
}
