<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\API\Values\Collection;

use Netgen\BlockManager\API\Values\Collection\QueryCreateStruct;
use Netgen\BlockManager\Collection\QueryType\QueryType;
use Netgen\BlockManager\Collection\QueryType\QueryTypeInterface;
use Netgen\BlockManager\Core\Values\Collection\Query;
use Netgen\BlockManager\Parameters\CompoundParameterDefinition;
use Netgen\BlockManager\Parameters\Parameter;
use Netgen\BlockManager\Parameters\ParameterDefinition;
use Netgen\BlockManager\Parameters\ParameterType;
use PHPUnit\Framework\TestCase;

final class QueryCreateStructTest extends TestCase
{
    /**
     * @var \Netgen\BlockManager\API\Values\Collection\QueryCreateStruct
     */
    private $struct;

    public function setUp(): void
    {
        $this->struct = new QueryCreateStruct($this->buildQueryType());
    }

    /**
     * @covers \Netgen\BlockManager\API\Values\Collection\QueryCreateStruct::fillDefaultParameters
     */
    public function testFillDefaultParameters(): void
    {
        $this->struct->fillDefaultParameters();

        $this->assertSame(
            [
                'css_class' => 'css_default',
                'css_id' => 'id_default',
                'compound' => true,
                'inner' => 'inner_default',
            ],
            $this->struct->getParameterValues()
        );
    }

    /**
     * @covers \Netgen\BlockManager\API\Values\Collection\QueryCreateStruct::fillParametersFromQuery
     */
    public function testFillParametersFromQuery(): void
    {
        $queryType = $this->buildQueryType();

        /** @var \Netgen\BlockManager\Parameters\CompoundParameterDefinition $compoundDefinition */
        $compoundDefinition = $queryType->getParameterDefinition('compound');

        $query = new Query(
            [
                'queryType' => $queryType,
                'parameters' => [
                    'css_class' => new Parameter(
                        [
                            'value' => 'css',
                            'parameterDefinition' => $queryType->getParameterDefinition('css_class'),
                        ]
                    ),
                    'inner' => new Parameter(
                        [
                            'value' => 'inner',
                            'parameterDefinition' => $compoundDefinition->getParameterDefinition('inner'),
                        ]
                    ),
                ],
            ]
        );

        $this->struct->fillParametersFromQuery($query);

        $this->assertSame(
            [
                'css_class' => 'css',
                'css_id' => null,
                'compound' => null,
                'inner' => 'inner',
            ],
            $this->struct->getParameterValues()
        );
    }

    /**
     * @covers \Netgen\BlockManager\API\Values\Collection\QueryCreateStruct::fillParametersFromHash
     */
    public function testFillParametersFromHash(): void
    {
        $initialValues = [
            'css_class' => 'css',
            'css_id' => 'id',
            'compound' => false,
            'inner' => 'inner',
        ];

        $this->struct->fillParametersFromHash($initialValues);

        $this->assertSame(
            [
                'css_class' => 'css',
                'css_id' => 'id',
                'compound' => false,
                'inner' => 'inner',
            ],
            $this->struct->getParameterValues()
        );
    }

    /**
     * @covers \Netgen\BlockManager\API\Values\Collection\QueryCreateStruct::fillParametersFromHash
     */
    public function testFillParametersFromHashWithMissingValues(): void
    {
        $initialValues = [
            'css_class' => 'css',
            'inner' => 'inner',
        ];

        $this->struct->fillParametersFromHash($initialValues);

        $this->assertSame(
            [
                'css_class' => 'css',
                'css_id' => 'id_default',
                'compound' => true,
                'inner' => 'inner',
            ],
            $this->struct->getParameterValues()
        );
    }

    private function buildQueryType(): QueryTypeInterface
    {
        $compoundParameter = new CompoundParameterDefinition(
            [
                'name' => 'compound',
                'type' => new ParameterType\Compound\BooleanType(),
                'defaultValue' => true,
                'parameterDefinitions' => [
                    'inner' => new ParameterDefinition(
                        [
                            'name' => 'inner',
                            'type' => new ParameterType\TextLineType(),
                            'defaultValue' => 'inner_default',
                        ]
                    ),
                ],
            ]
        );

        $parameterDefinitions = [
            'css_class' => new ParameterDefinition(
                [
                    'name' => 'css_class',
                    'type' => new ParameterType\TextLineType(),
                    'defaultValue' => 'css_default',
                ]
            ),
            'css_id' => new ParameterDefinition(
                [
                    'name' => 'css_id',
                    'type' => new ParameterType\TextLineType(),
                    'defaultValue' => 'id_default',
                ]
            ),
            'compound' => $compoundParameter,
        ];

        return new QueryType(['parameterDefinitions' => $parameterDefinitions]);
    }
}
