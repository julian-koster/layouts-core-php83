<?php

declare(strict_types=1);

namespace Netgen\Bundle\BlockManagerBundle\Tests\Design\Twig;

use Twig\Loader\FilesystemLoader as BaseFilesystemLoader;

class LegacyFilesystemLoader extends BaseFilesystemLoader
{
    public function getSource($name): string
    {
        return '';
    }
}
