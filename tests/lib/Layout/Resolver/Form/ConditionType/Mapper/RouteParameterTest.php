<?php

namespace Netgen\BlockManager\Tests\Layout\Resolver\Form\ConditionType\Mapper;

use Netgen\BlockManager\Form\KeyValuesType;
use Netgen\BlockManager\Layout\Resolver\Form\ConditionType\Mapper\RouteParameter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class RouteParameterTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Layout\Resolver\Form\ConditionType\MapperInterface
     */
    private $mapper;

    public function setUp()
    {
        $this->mapper = new RouteParameter();
    }

    /**
     * @covers \Netgen\BlockManager\Layout\Resolver\Form\ConditionType\Mapper\RouteParameter::getFormType
     */
    public function testGetFormType()
    {
        $this->assertEquals(KeyValuesType::class, $this->mapper->getFormType());
    }

    /**
     * @covers \Netgen\BlockManager\Layout\Resolver\Form\ConditionType\Mapper\RouteParameter::getFormOptions
     */
    public function testGetFormOptions()
    {
        $this->assertEquals(
            array(
                'label' => false,
                'required' => false,
                'key_name' => 'parameter_name',
                'key_label' => 'layout_resolver.condition.route_parameter.parameter_name',
                'values_name' => 'parameter_values',
                'values_label' => 'layout_resolver.condition.route_parameter.parameter_values',
                'values_type' => TextType::class,
                'values_options' => array(
                    'empty_data' => ' ',
                ),
            ),
            $this->mapper->getFormOptions()
        );
    }
}
