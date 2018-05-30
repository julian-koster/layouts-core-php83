<?php

namespace Netgen\Bundle\BlockManagerBundle\Tests\Controller\API\V1\Collection;

use Netgen\Bundle\BlockManagerBundle\Tests\Controller\API\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

final class DeleteItemTest extends JsonApiTestCase
{
    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Collection\DeleteItem::__construct
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Collection\DeleteItem::__invoke
     */
    public function testDeleteItem()
    {
        $data = $this->jsonEncode([]);

        $this->client->request(
            'DELETE',
            '/bm/api/v1/collections/items/7',
            [],
            [],
            [],
            $data
        );

        $this->assertEmptyResponse($this->client->getResponse());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Collection\DeleteItem::__invoke
     */
    public function testDeleteItemWithNonExistentItem()
    {
        $data = $this->jsonEncode([]);

        $this->client->request(
            'DELETE',
            '/bm/api/v1/collections/items/9999',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_NOT_FOUND,
            'Could not find item with identifier "9999"'
        );
    }
}
