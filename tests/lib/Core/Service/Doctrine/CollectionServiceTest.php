<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Core\Service\Doctrine;

use Netgen\BlockManager\Tests\Core\Service\CollectionServiceTest as BaseCollectionServiceTest;
use Netgen\BlockManager\Tests\Persistence\Doctrine\TestCaseTrait;

final class CollectionServiceTest extends BaseCollectionServiceTest
{
    use TestCaseTrait;

    public function tearDown(): void
    {
        $this->closeDatabase();
    }

    /**
     * Prepares the prerequisites for using services in tests.
     */
    protected function preparePersistence(): void
    {
        $this->persistenceHandler = $this->createPersistenceHandler();
    }
}
