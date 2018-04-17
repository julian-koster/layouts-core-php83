<?php

namespace Netgen\Bundle\BlockManagerBundle\Tests\Controller\API\V1;

use Netgen\BlockManager\API\Values\Collection\Collection;
use Netgen\BlockManager\API\Values\Collection\Item;
use Netgen\Bundle\BlockManagerBundle\Tests\Controller\API\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

final class BlockCollectionControllerTest extends JsonApiTestCase
{
    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::__construct
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::checkPermissions
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::loadCollectionResult
     */
    public function testLoadCollectionResult()
    {
        $this->client->request('GET', '/bm/api/v1/en/blocks/35/collections/default/result');

        $this->assertResponse(
            $this->client->getResponse(),
            'v1/block_collections/load_collection_result',
            Response::HTTP_OK
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::loadCollectionResult
     */
    public function testLoadCollectionResultWithNonExistentBlock()
    {
        $this->client->request('GET', '/bm/api/v1/en/blocks/9999/collections/default/result');

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_NOT_FOUND,
            'Could not find block with identifier "9999"'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::loadCollectionResult
     */
    public function testLoadCollectionResultWithNonExistentCollectionReference()
    {
        $this->client->request('GET', '/bm/api/v1/en/blocks/31/collections/unknown/result');

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'Collection with "unknown" identifier does not exist in the block.'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItems()
    {
        $data = $this->jsonEncode(
            [
                'items' => [
                    [
                        'type' => Item::TYPE_MANUAL,
                        'value' => 73,
                        'value_type' => 'ezlocation',
                        'position' => 3,
                    ],
                    [
                        'type' => Item::TYPE_MANUAL,
                        'value' => 74,
                        'value_type' => 'ezlocation',
                    ],
                ],
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/items',
            [],
            [],
            [],
            $data
        );

        $this->assertEmptyResponse($this->client->getResponse());
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithNonExistentBlock()
    {
        $data = $this->jsonEncode(
            [
                'items' => [
                    [
                        'type' => Item::TYPE_MANUAL,
                        'value' => 73,
                        'value_type' => 'ezlocation',
                        'position' => 3,
                    ],
                    [
                        'type' => Item::TYPE_MANUAL,
                        'value' => 74,
                        'value_type' => 'ezlocation',
                    ],
                ],
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/9999/collections/default/items',
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

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithNonExistentCollectionReference()
    {
        $data = $this->jsonEncode(
            [
                'items' => [
                    [
                        'type' => Item::TYPE_MANUAL,
                        'value' => 73,
                        'value_type' => 'ezlocation',
                        'position' => 3,
                    ],
                    [
                        'type' => Item::TYPE_MANUAL,
                        'value' => 74,
                        'value_type' => 'ezlocation',
                    ],
                ],
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/unknown/items',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'Collection with "unknown" identifier does not exist in the block.'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithEmptyItems()
    {
        $data = $this->jsonEncode(
            [
                'items' => [],
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/items',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'There was an error validating "items": This value should not be blank.'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithInvalidItems()
    {
        $data = $this->jsonEncode(
            [
                'items' => 42,
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/items',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'There was an error validating "items": Expected argument of type "array or Traversable", "integer" given'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithMissingItems()
    {
        $data = $this->jsonEncode([]);

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/items',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'There was an error validating "items": This value should not be blank.'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithInvalidItemType()
    {
        $data = $this->jsonEncode(
            [
                'items' => [
                    [
                        'type' => 'type',
                        'value' => 73,
                        'value_type' => 'ezlocation',
                        'position' => 3,
                    ],
                ],
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/items',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'There was an error validating "[0][type]": This value should be of type int.'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithMissingItemType()
    {
        $data = $this->jsonEncode(
            [
                'items' => [
                    [
                        'value' => 73,
                        'value_type' => 'ezlocation',
                        'position' => 3,
                    ],
                ],
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/items',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'There was an error validating "[0][type]": This field is missing.'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithInvalidValue()
    {
        $data = $this->jsonEncode(
            [
                'items' => [
                    [
                        'type' => Item::TYPE_MANUAL,
                        'value' => [42],
                        'value_type' => 'ezlocation',
                        'position' => 3,
                    ],
                ],
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/items',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'There was an error validating "[0][value]": This value should be of type scalar.'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithMissingValue()
    {
        $data = $this->jsonEncode(
            [
                'items' => [
                    [
                        'type' => Item::TYPE_MANUAL,
                        'value_type' => 'ezlocation',
                        'position' => 3,
                    ],
                ],
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/items',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'There was an error validating "[0][value]": This field is missing.'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithInvalidValueType()
    {
        $data = $this->jsonEncode(
            [
                'items' => [
                    [
                        'type' => Item::TYPE_MANUAL,
                        'value' => 73,
                        'value_type' => 42,
                        'position' => 3,
                    ],
                ],
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/items',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'There was an error validating "[0][value_type]": This value should be of type string.'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithMissingValueType()
    {
        $data = $this->jsonEncode(
            [
                'items' => [
                    [
                        'type' => Item::TYPE_MANUAL,
                        'value' => 73,
                        'position' => 3,
                    ],
                ],
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/items',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'There was an error validating "[0][value_type]": This field is missing.'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithInvalidPosition()
    {
        $data = $this->jsonEncode(
            [
                'items' => [
                    [
                        'type' => Item::TYPE_MANUAL,
                        'value' => 73,
                        'value_type' => 'ezlocation',
                        'position' => '3',
                    ],
                ],
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/items',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'There was an error validating "[0][position]": This value should be of type int.'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithMissingPosition()
    {
        $data = $this->jsonEncode(
            [
                'items' => [
                    [
                        'type' => Item::TYPE_MANUAL,
                        'value' => 73,
                        'value_type' => 'ezlocation',
                    ],
                ],
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/featured/items',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_BAD_REQUEST,
            'There was an error validating "position": This value should not be blank.'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::addItems
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateAddItems
     */
    public function testAddItemsWithOutOfRangePosition()
    {
        $data = $this->jsonEncode(
            [
                'items' => [
                    [
                        'type' => Item::TYPE_MANUAL,
                        'value' => 73,
                        'value_type' => 'ezlocation',
                        'position' => 9999,
                    ],
                ],
            ]
        );

        $this->client->request(
            'POST',
            '/bm/api/v1/en/blocks/31/collections/default/items',
            [],
            [],
            [],
            $data
        );

        $this->assertException(
            $this->client->getResponse(),
            Response::HTTP_UNPROCESSABLE_ENTITY,
            'Argument "position" has an invalid state. Position is out of range.'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::changeCollectionType
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateChangeCollectionType
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
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::changeCollectionType
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateChangeCollectionType
     */
    public function testChangeCollectionTypeFromManualToDynamic()
    {
        $data = $this->jsonEncode(
            [
                'new_type' => Collection::TYPE_DYNAMIC,
                'query_type' => 'ezcontent_search',
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
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::changeCollectionType
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateChangeCollectionType
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
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\BlockCollectionController::changeCollectionType
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::getCollectionConfig
     * @covers \Netgen\Bundle\BlockManagerBundle\Controller\API\V1\Validator\BlockCollectionValidator::validateChangeCollectionType
     */
    public function testChangeCollectionTypeFromDynamicToDynamic()
    {
        $data = $this->jsonEncode(
            [
                'new_type' => Collection::TYPE_DYNAMIC,
                'query_type' => 'ezcontent_search',
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
