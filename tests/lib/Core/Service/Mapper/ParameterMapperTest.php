<?php

namespace Netgen\BlockManager\Tests\Core\Service\Mapper;

use Netgen\BlockManager\Core\Service\Mapper\ParameterMapper;
use Netgen\BlockManager\Parameters\ParameterValue;
use Netgen\BlockManager\Tests\Block\Stubs\BlockDefinition;
use Netgen\BlockManager\Tests\Block\Stubs\BlockDefinitionHandlerWithCompoundParameter;
use Netgen\BlockManager\Tests\Block\Stubs\BlockDefinitionHandlerWithUntranslatableCompoundParameter;
use PHPUnit\Framework\TestCase;

class ParameterMapperTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\Core\Service\Mapper\ParameterMapper
     */
    protected $mapper;

    /**
     * Sets up the tests.
     */
    public function setUp()
    {
        $this->mapper = new ParameterMapper();
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\ParameterMapper::mapParameters
     */
    public function testMapParameters()
    {
        $blockDefinition = new BlockDefinition(
            'block_definition',
            array(),
            new BlockDefinitionHandlerWithCompoundParameter()
        );

        $mappedParameters = $this->mapper->mapParameters(
            $blockDefinition,
            array(
                'css_id' => 'some-id',
                'compound' => true,
                'inner' => 'inner-value',
            )
        );

        $this->assertCount(4, $mappedParameters);
        $this->assertArrayHasKey('css_class', $mappedParameters);
        $this->assertArrayHasKey('css_id', $mappedParameters);
        $this->assertArrayHasKey('compound', $mappedParameters);
        $this->assertArrayHasKey('inner', $mappedParameters);

        $this->assertInstanceOf(ParameterValue::class, $mappedParameters['css_class']);
        $this->assertEquals($blockDefinition->getParameter('css_class'), $mappedParameters['css_class']->getParameter());
        $this->assertEquals('some-class', $mappedParameters['css_class']->getValue());
        $this->assertFalse($mappedParameters['css_class']->isEmpty());

        $this->assertInstanceOf(ParameterValue::class, $mappedParameters['css_id']);
        $this->assertEquals($blockDefinition->getParameter('css_id'), $mappedParameters['css_id']->getParameter());
        $this->assertEquals('some-id', $mappedParameters['css_id']->getValue());
        $this->assertFalse($mappedParameters['css_id']->isEmpty());

        $this->assertInstanceOf(ParameterValue::class, $mappedParameters['compound']);
        $this->assertEquals($blockDefinition->getParameter('compound'), $mappedParameters['compound']->getParameter());
        $this->assertTrue($mappedParameters['compound']->getValue());
        $this->assertFalse($mappedParameters['compound']->isEmpty());

        $this->assertInstanceOf(ParameterValue::class, $mappedParameters['inner']);
        $this->assertEquals($blockDefinition->getParameter('compound')->getParameter('inner'), $mappedParameters['inner']->getParameter());
        $this->assertEquals('inner-value', $mappedParameters['inner']->getValue());
        $this->assertFalse($mappedParameters['inner']->isEmpty());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\ParameterMapper::serializeValues
     */
    public function testSerializeValues()
    {
        $blockDefinition = new BlockDefinition(
            'block_definition',
            array(),
            new BlockDefinitionHandlerWithCompoundParameter()
        );

        $serializedParameters = $this->mapper->serializeValues(
            $blockDefinition,
            array(
                'css_class' => 'some-class',
                'compound' => true,
                'inner' => 'inner-value',
            )
        );

        $this->assertEquals(
            array(
                'css_class' => 'some-class',
                'compound' => true,
                'inner' => 'inner-value',
            ),
            $serializedParameters
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\Mapper\ParameterMapper::extractUntranslatableParameters
     */
    public function testExtractUntranslatableParameters()
    {
        $blockDefinition = new BlockDefinition(
            'block_definition',
            array(),
            new BlockDefinitionHandlerWithUntranslatableCompoundParameter()
        );

        $untranslatableParams = $this->mapper->extractUntranslatableParameters(
            $blockDefinition,
            array(
                'css_id' => 'some-id',
                'css_class' => 'some-class',
                'compound' => true,
                'inner' => 'inner-value',
            )
        );

        $this->assertEquals(
            array(
                'css_id' => 'some-id',
                'compound' => true,
                'inner' => 'inner-value',
                'other' => null,
            ),
            $untranslatableParams
        );
    }
}
