<?php

namespace Netgen\BlockManager\Tests\Context\Stubs;

use Netgen\BlockManager\Context\ContextInterface;
use Netgen\BlockManager\Context\ContextProviderInterface;

final class ContextProvider implements ContextProviderInterface
{
    /**
     * @var array
     */
    private $variables;

    public function __construct(array $variables = [])
    {
        $this->variables = $variables;
    }

    public function provideContext(ContextInterface $context)
    {
        $context->add($this->variables);
    }
}
