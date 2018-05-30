<?php

namespace Netgen\Bundle\BlockManagerBundle\Tests\Controller\API\V1\Block;

use Netgen\Bundle\BlockManagerBundle\Tests\Controller\API\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

final class DeleteTest extends JsonApiTestCase
{
    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Block\Delete::__construct
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Block\Delete::__invoke
     */
    public function testDelete()
    {
        $data = $this->jsonEncode([]);

        $this->client->request(
            'DELETE',
            '/bm/api/v1/en/blocks/31',
            [],
            [],
            [],
            $data
        );

        $this->assertEmptyResponse($this->client->getResponse());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Block\Delete::__invoke
     */
    public function testDeleteWithNonExistentBlock()
    {
        $data = $this->jsonEncode([]);

        $this->client->request(
            'DELETE',
            '/bm/api/v1/en/blocks/9999',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_NOT_FOUND,
            'Could not find block with identifier "9999"'
        );
    }
}
