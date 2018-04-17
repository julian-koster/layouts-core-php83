<?php

namespace Netgen\Bundle\BlockManagerBundle\Templating\Twig\Extension;

use Netgen\Bundle\BlockManagerBundle\Templating\Twig\Runtime\ItemRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ItemExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'ngbm_item_path',
                [ItemRuntime::class, 'getItemPath'],
                [
                    'is_safe' => ['html'],
                ]
            ),
        ];
    }
}
