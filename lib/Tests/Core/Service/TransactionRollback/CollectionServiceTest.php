<?php

namespace Netgen\BlockManager\Tests\Core\Service\TransactionRollback;

use Netgen\BlockManager\API\Values\CollectionCreateStruct;
use Netgen\BlockManager\API\Values\CollectionUpdateStruct;
use Netgen\BlockManager\API\Values\ItemCreateStruct;
use Netgen\BlockManager\Core\Service\Validator\CollectionValidator;
use Netgen\BlockManager\Persistence\Values\Collection\Collection as PersistenceCollection;
use Netgen\BlockManager\Core\Values\Collection\Collection;
use Netgen\BlockManager\Core\Values\Collection\Item;
use Netgen\BlockManager\Core\Values\Collection\Query;
use Netgen\BlockManager\Core\Values\QueryCreateStruct;
use Netgen\BlockManager\Core\Values\QueryUpdateStruct;
use Netgen\BlockManager\Persistence\Handler\CollectionHandler;
use Exception;

class CollectionServiceTest extends \PHPUnit_Framework_TestCase
{
    use TestCase;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $collectionHandlerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $collectionValidatorMock;

    /**
     * @var \Netgen\BlockManager\API\Service\CollectionService
     */
    protected $collectionService;

    /**
     * Sets up the tests.
     */
    public function setUp()
    {
        $this->preparePersistence();

        $this->collectionHandlerMock = $this->getMockBuilder(CollectionHandler::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->persistenceHandler
            ->expects($this->any())
            ->method('getCollectionHandler')
            ->will($this->returnValue($this->collectionHandlerMock));

        $this->collectionValidatorMock = $this->getMockBuilder(CollectionValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->collectionService = $this->createCollectionService($this->collectionValidatorMock);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::createCollection
     * @expectedException \Exception
     */
    public function testCreateCollection()
    {
        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('createCollection')
            ->will($this->throwException(new Exception()));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->createCollection(new CollectionCreateStruct());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::updateCollection
     * @expectedException \Exception
     */
    public function testUpdateCollection()
    {
        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('updateCollection')
            ->will($this->throwException(new Exception()));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->updateCollection(
            new Collection(array('status' => Collection::STATUS_DRAFT)),
            new CollectionUpdateStruct()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::copyCollection
     * @expectedException \Exception
     */
    public function testCopyCollection()
    {
        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('copyCollection')
            ->will($this->throwException(new Exception()));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->copyCollection(new Collection());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::createCollectionStatus
     * @expectedException \Exception
     */
    public function testCreateCollectionStatus()
    {
        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('createCollectionStatus')
            ->will($this->throwException(new Exception()));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->createCollectionStatus(new Collection(), Collection::STATUS_ARCHIVED);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::publishCollection
     * @expectedException \Exception
     */
    public function testPublishCollection()
    {
        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('deleteCollection')
            ->will($this->throwException(new Exception()));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->publishCollection(new Collection(array('status' => Collection::STATUS_DRAFT)));
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::deleteCollection
     * @expectedException \Exception
     */
    public function testDeleteCollection()
    {
        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('deleteCollection')
            ->will($this->throwException(new Exception()));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->deleteCollection(new Collection());
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::addItem
     * @expectedException \Exception
     */
    public function testAddItem()
    {
        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('addItem')
            ->will($this->throwException(new Exception()));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->addItem(
            new Collection(array('type' => Collection::TYPE_MANUAL, 'status' => Collection::STATUS_DRAFT)),
            new ItemCreateStruct(array('type' => Item::TYPE_MANUAL))
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::moveItem
     * @expectedException \Exception
     */
    public function testMoveItem()
    {
        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('loadCollection')
            ->will($this->returnValue(new PersistenceCollection()));

        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('moveItem')
            ->will($this->throwException(new Exception()));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->moveItem(new Item(array('status' => Collection::STATUS_DRAFT)), 0);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::deleteItem
     * @expectedException \Exception
     */
    public function testDeleteItem()
    {
        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('deleteItem')
            ->will($this->throwException(new Exception()));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->deleteItem(new Item(array('status' => Collection::STATUS_DRAFT)));
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::addQuery
     * @expectedException \Exception
     */
    public function testAddQuery()
    {
        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('addQuery')
            ->will($this->throwException(new Exception()));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->addQuery(
            new Collection(array('status' => Collection::STATUS_DRAFT)),
            new QueryCreateStruct()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::updateQuery
     * @expectedException \Exception
     */
    public function testUpdateQuery()
    {
        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('updateQuery')
            ->will($this->throwException(new Exception()));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->updateQuery(
            new Query(array('status' => Collection::STATUS_DRAFT)),
            new QueryUpdateStruct()
        );
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::moveQuery
     * @expectedException \Exception
     */
    public function testMoveQuery()
    {
        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('moveQuery')
            ->will($this->throwException(new Exception()));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->moveQuery(new Query(array('status' => Collection::STATUS_DRAFT)), 0);
    }

    /**
     * @covers \Netgen\BlockManager\Core\Service\CollectionService::deleteQuery
     * @expectedException \Exception
     */
    public function testDeleteQuery()
    {
        $this->collectionHandlerMock
            ->expects($this->once())
            ->method('deleteQuery')
            ->will($this->throwException(new Exception()));

        $this->persistenceHandler
            ->expects($this->once())
            ->method('rollbackTransaction');

        $this->collectionService->deleteQuery(new Query(array('status' => Collection::STATUS_DRAFT)));
    }
}
