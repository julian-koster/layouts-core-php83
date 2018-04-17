<?php

namespace Netgen\Bundle\BlockManagerAdminBundle\Tests\Form\Admin\Type;

use Netgen\BlockManager\Core\Values\Layout\Layout;
use Netgen\BlockManager\Tests\TestCase\FormTestCase;
use Netgen\Bundle\BlockManagerAdminBundle\Form\Admin\Type\ClearLayoutsCacheType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ClearLayoutsCacheTypeTest extends FormTestCase
{
    /**
     * @var \Netgen\BlockManager\API\Values\Block\Block
     */
    private $layouts;

    public function setUp()
    {
        parent::setUp();

        $this->layouts = [42 => new Layout(['id' => 42]), 24 => new Layout(['id' => 24])];
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function getMainType()
    {
        return new ClearLayoutsCacheType();
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerAdminBundle\Form\Admin\Type\ClearLayoutsCacheType::buildForm
     * @covers \Netgen\Bundle\BlockManagerAdminBundle\Form\Admin\Type\ClearLayoutsCacheType::buildView
     */
    public function testSubmitValidData()
    {
        $submittedData = [
            'layouts' => [42],
        ];

        $form = $this->factory->create(
            ClearLayoutsCacheType::class,
            null,
            ['layouts' => $this->layouts]
        );

        $form->submit($submittedData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(['layouts' => [$this->layouts[42]]], $form->getData());

        $view = $form->createView();

        $this->assertArrayHasKey('layouts', $view->vars);
        $this->assertEquals($this->layouts, $view->vars['layouts']);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerAdminBundle\Form\Admin\Type\ClearLayoutsCacheType::configureOptions
     */
    public function testConfigureOptions()
    {
        $optionsResolver = new OptionsResolver();

        $this->formType->configureOptions($optionsResolver);

        $options = $optionsResolver->resolve(
            [
                'layouts' => $this->layouts,
            ]
        );

        $this->assertEquals($this->layouts, $options['layouts']);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerAdminBundle\Form\Admin\Type\ClearLayoutsCacheType::configureOptions
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     * @expectedExceptionMessage The option "layouts" with value 42 is expected to be of type "array", but is of type "integer".
     */
    public function testConfigureOptionsWithInvalidLayouts()
    {
        $optionsResolver = new OptionsResolver();

        $this->formType->configureOptions($optionsResolver);

        $optionsResolver->resolve(
            [
                'layouts' => 42,
            ]
        );
    }
}
