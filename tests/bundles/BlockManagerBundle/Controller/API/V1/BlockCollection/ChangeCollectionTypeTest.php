<?php

namespace Netgen\Bundle\BlockManagerBundle\Tests\Controller\API\V1\BlockCollection;

use Netgen\BlockManager\API\Values\Collection\Collection;
use Netgen\Bundle\BlockManagerBundle\Tests\Controller\API\JsonApiTestCase;

final class ChangeCollectionTypeTest extends JsonApiTestCase
{
    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollection\ChangeCollectionType::__construct
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollection\ChangeCollectionType::__invoke
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollection\Utils\ChangeCollectionTypeValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollection\Utils\ChangeCollectionTypeValidator::validateChangeCollectionType
     */
    public function testChangeCollectionTypeFromManualToManual()
    {
        $data = $this->jsonEncode(
            [
                'new_type' => Collection::TYPE_MANUAL,
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/change_type',
            [],
            [],
            [],
            $data
        );

        $this->assertEmptyResponse($this->client->getResponse());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollection\ChangeCollectionType::__invoke
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollection\Utils\ChangeCollectionTypeValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollection\Utils\ChangeCollectionTypeValidator::validateChangeCollectionType
     */
    public function testChangeCollectionTypeFromManualToDynamic()
    {
        $data = $this->jsonEncode(
            [
                'new_type' => Collection::TYPE_DYNAMIC,
                'query_type' => 'my_query_type',
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/change_type',
            [],
            [],
            [],
            $data
        );

        $this->assertEmptyResponse($this->client->getResponse());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollection\ChangeCollectionType::__invoke
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollection\Utils\ChangeCollectionTypeValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollection\Utils\ChangeCollectionTypeValidator::validateChangeCollectionType
     */
    public function testChangeCollectionTypeFromDynamicToManual()
    {
        $data = $this->jsonEncode(
            [
                'new_type' => Collection::TYPE_MANUAL,
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/featured/change_type',
            [],
            [],
            [],
            $data
        );

        $this->assertEmptyResponse($this->client->getResponse());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollection\ChangeCollectionType::__invoke
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollection\Utils\ChangeCollectionTypeValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollection\Utils\ChangeCollectionTypeValidator::validateChangeCollectionType
     */
    public function testChangeCollectionTypeFromDynamicToDynamic()
    {
        $data = $this->jsonEncode(
            [
                'new_type' => Collection::TYPE_DYNAMIC,
                'query_type' => 'my_query_type',
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/featured/change_type',
            [],
            [],
            [],
            $data
        );

        $this->assertEmptyResponse($this->client->getResponse());
    }
}
