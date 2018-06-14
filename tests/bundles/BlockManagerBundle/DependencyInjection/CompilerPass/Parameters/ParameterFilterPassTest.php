<?php

declare(strict_types=1);

namespace Netgen\Bundle\BlockManagerBundle\Tests\DependencyInjection\CompilerPass\Parameters;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\Parameters\ParameterFilterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;
use Symfony\Component\DependencyInjection\Reference;

final class ParameterFilterPassTest extends AbstractCompilerPassTestCase
{
    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\Parameters\ParameterFilterPass::process
     */
    public function testProcess(): void
    {
        $parameterFilterRegistry = new Definition();
        $parameterFilterRegistry->addArgument([]);
        $this->setDefinition('netgen_block_manager.parameters.registry.parameter_filter', $parameterFilterRegistry);

        $filter1 = new Definition();
        $filter1->addTag('netgen_block_manager.parameters.parameter_filter', ['type' => 'html']);
        $this->setDefinition('netgen_block_manager.parameters.parameter_filter.test1', $filter1);

        $filter2 = new Definition();
        $filter2->addTag('netgen_block_manager.parameters.parameter_filter', ['priority' => 5, 'type' => 'html']);
        $this->setDefinition('netgen_block_manager.parameters.parameter_filter.test2', $filter2);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'netgen_block_manager.parameters.registry.parameter_filter',
            'addParameterFilter',
            [
                'html',
                new Reference('netgen_block_manager.parameters.parameter_filter.test2'),
            ],
            0
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'netgen_block_manager.parameters.registry.parameter_filter',
            'addParameterFilter',
            [
                'html',
                new Reference('netgen_block_manager.parameters.parameter_filter.test1'),
            ],
            1
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\Parameters\ParameterFilterPass::process
     * @expectedException \Netgen\BlockManager\Exception\RuntimeException
     * @expectedExceptionMessage Parameter filter service definition must have a 'type' attribute in its' tag.
     */
    public function testProcessThrowsExceptionWithNoTypeIdentifier(): void
    {
        $this->setDefinition('netgen_block_manager.parameters.registry.parameter_filter', new Definition());

        $filter = new Definition();
        $filter->addTag('netgen_block_manager.parameters.parameter_filter');
        $this->setDefinition('netgen_block_manager.parameters.parameter_filter.test', $filter);

        $this->compile();
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\CompilerPass\Parameters\ParameterFilterPass::process
     */
    public function testProcessWithEmptyContainer(): void
    {
        $this->compile();

        $this->assertInstanceOf(FrozenParameterBag::class, $this->container->getParameterBag());
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ParameterFilterPass());
    }
}
