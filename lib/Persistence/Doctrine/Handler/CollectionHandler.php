<?php

namespace Netgen\BlockManager\Persistence\Doctrine\Handler;

use Netgen\BlockManager\Exception\BadStateException;
use Netgen\BlockManager\Exception\NotFoundException;
use Netgen\BlockManager\Persistence\Doctrine\Helper\PositionHelper;
use Netgen\BlockManager\Persistence\Doctrine\Mapper\CollectionMapper;
use Netgen\BlockManager\Persistence\Doctrine\QueryHandler\CollectionQueryHandler;
use Netgen\BlockManager\Persistence\Handler\CollectionHandlerInterface;
use Netgen\BlockManager\Persistence\Values\Collection\Collection;
use Netgen\BlockManager\Persistence\Values\Collection\CollectionCreateStruct;
use Netgen\BlockManager\Persistence\Values\Collection\CollectionUpdateStruct;
use Netgen\BlockManager\Persistence\Values\Collection\Item;
use Netgen\BlockManager\Persistence\Values\Collection\ItemCreateStruct;
use Netgen\BlockManager\Persistence\Values\Collection\ItemUpdateStruct;
use Netgen\BlockManager\Persistence\Values\Collection\Query;
use Netgen\BlockManager\Persistence\Values\Collection\QueryCreateStruct;
use Netgen\BlockManager\Persistence\Values\Collection\QueryTranslationUpdateStruct;

final class CollectionHandler implements CollectionHandlerInterface
{
    /**
     * @var \Netgen\BlockManager\Persistence\Doctrine\QueryHandler\CollectionQueryHandler
     */
    private $queryHandler;

    /**
     * @var \Netgen\BlockManager\Persistence\Doctrine\Mapper\CollectionMapper
     */
    private $collectionMapper;

    /**
     * @var \Netgen\BlockManager\Persistence\Doctrine\Helper\PositionHelper
     */
    private $positionHelper;

    public function __construct(
        CollectionQueryHandler $queryHandler,
        CollectionMapper $collectionMapper,
        PositionHelper $positionHelper
    ) {
        $this->queryHandler = $queryHandler;
        $this->collectionMapper = $collectionMapper;
        $this->positionHelper = $positionHelper;
    }

    public function loadCollection($collectionId, $status)
    {
        $data = $this->queryHandler->loadCollectionData($collectionId, $status);

        if (empty($data)) {
            throw new NotFoundException('collection', $collectionId);
        }

        $data = $this->collectionMapper->mapCollections($data);

        return reset($data);
    }

    public function loadItem($itemId, $status)
    {
        $data = $this->queryHandler->loadItemData($itemId, $status);

        if (empty($data)) {
            throw new NotFoundException('item', $itemId);
        }

        $data = $this->collectionMapper->mapItems($data);

        return reset($data);
    }

    public function loadItemWithPosition(Collection $collection, $position)
    {
        $data = $this->queryHandler->loadItemWithPositionData($collection, $position);

        if (empty($data)) {
            throw new NotFoundException(
                sprintf(
                    'item in collection with ID "%d" at position %d',
                    $collection->id,
                    $position
                )
            );
        }

        $data = $this->collectionMapper->mapItems($data);

        return reset($data);
    }

    public function loadCollectionItems(Collection $collection)
    {
        return $this->collectionMapper->mapItems(
            $this->queryHandler->loadCollectionItemsData($collection)
        );
    }

    public function loadQuery($queryId, $status)
    {
        $data = $this->queryHandler->loadQueryData($queryId, $status);

        if (empty($data)) {
            throw new NotFoundException('query', $queryId);
        }

        $query = $this->collectionMapper->mapQuery($data);

        $collection = $this->loadCollection($query->collectionId, $query->status);

        $query->isTranslatable = $collection->isTranslatable;
        $query->mainLocale = $collection->mainLocale;
        $query->alwaysAvailable = $collection->alwaysAvailable;

        return $query;
    }

    public function loadCollectionQuery(Collection $collection)
    {
        $data = $this->queryHandler->loadCollectionQueryData($collection);

        if (empty($data)) {
            throw new NotFoundException('query for collection', $collection->id);
        }

        $query = $this->collectionMapper->mapQuery($data);

        $query->isTranslatable = $collection->isTranslatable;
        $query->mainLocale = $collection->mainLocale;
        $query->alwaysAvailable = $collection->alwaysAvailable;

        return $query;
    }

    public function collectionExists($collectionId, $status)
    {
        return $this->queryHandler->collectionExists($collectionId, $status);
    }

    public function createCollection(CollectionCreateStruct $collectionCreateStruct)
    {
        $newCollection = new Collection(
            [
                'status' => $collectionCreateStruct->status,
                'offset' => $collectionCreateStruct->offset,
                'limit' => $collectionCreateStruct->limit,
                'isTranslatable' => $collectionCreateStruct->isTranslatable,
                'alwaysAvailable' => $collectionCreateStruct->alwaysAvailable,
                'mainLocale' => $collectionCreateStruct->mainLocale,
                'availableLocales' => [$collectionCreateStruct->mainLocale],
            ]
        );

        $newCollection = $this->queryHandler->createCollection($newCollection);

        $this->queryHandler->createCollectionTranslation(
            $newCollection,
            $collectionCreateStruct->mainLocale
        );

        return $newCollection;
    }

    public function createCollectionTranslation(Collection $collection, $locale, $sourceLocale)
    {
        if (in_array($locale, $collection->availableLocales, true)) {
            throw new BadStateException('locale', 'Collection already has the provided locale.');
        }

        if (!in_array($sourceLocale, $collection->availableLocales, true)) {
            throw new BadStateException('locale', 'Collection does not have the provided source locale.');
        }

        $updatedCollection = clone $collection;
        $updatedCollection->availableLocales[] = $locale;

        $this->queryHandler->createCollectionTranslation($collection, $locale);

        $query = null;
        try {
            $query = $this->loadCollectionQuery($collection);
        } catch (NotFoundException $e) {
            // Do nothing
        }

        if ($query instanceof Query) {
            $updatedQuery = clone $query;
            $updatedQuery->availableLocales[] = $locale;
            $updatedQuery->parameters[$locale] = $updatedQuery->parameters[$sourceLocale];

            $this->queryHandler->createQueryTranslation($updatedQuery, $locale);
        }

        return $updatedCollection;
    }

    public function setMainTranslation(Collection $collection, $mainLocale)
    {
        if (!in_array($mainLocale, $collection->availableLocales, true)) {
            throw new BadStateException('mainLocale', 'Collection does not have the provided locale.');
        }

        $updatedCollection = clone $collection;
        $updatedCollection->mainLocale = $mainLocale;

        $this->queryHandler->updateCollection($updatedCollection);

        return $updatedCollection;
    }

    public function updateCollection(Collection $collection, CollectionUpdateStruct $collectionUpdateStruct)
    {
        $updatedCollection = clone $collection;

        if ($collectionUpdateStruct->offset !== null) {
            $updatedCollection->offset = (int) $collectionUpdateStruct->offset;
        }

        if ($collectionUpdateStruct->limit !== null) {
            // Limit can be 0 to indicate that we want to disable the limit
            $updatedCollection->limit = $collectionUpdateStruct->limit !== 0 ?
                (int) $collectionUpdateStruct->limit :
                null;
        }

        if ($collectionUpdateStruct->isTranslatable !== null) {
            $updatedCollection->isTranslatable = (bool) $collectionUpdateStruct->isTranslatable;
        }

        if ($collectionUpdateStruct->alwaysAvailable !== null) {
            $updatedCollection->alwaysAvailable = (bool) $collectionUpdateStruct->alwaysAvailable;
        }

        $this->queryHandler->updateCollection($updatedCollection);

        return $updatedCollection;
    }

    public function copyCollection(Collection $collection)
    {
        $newCollection = clone $collection;
        $newCollection->id = null;

        $newCollection = $this->queryHandler->createCollection($newCollection);

        foreach ($newCollection->availableLocales as $locale) {
            $this->queryHandler->createCollectionTranslation($newCollection, $locale);
        }

        $collectionItems = $this->loadCollectionItems($collection);

        foreach ($collectionItems as $collectionItem) {
            $newItem = clone $collectionItem;

            $newItem->id = null;
            $newItem->collectionId = $newCollection->id;

            $this->queryHandler->addItem($newItem);
        }

        $collectionQuery = null;
        try {
            $collectionQuery = $this->loadCollectionQuery($collection);
        } catch (NotFoundException $e) {
            // Do nothing
        }

        if ($collectionQuery instanceof Query) {
            $newQuery = clone $collectionQuery;

            $newQuery->id = null;
            $newQuery->collectionId = $newCollection->id;

            $this->queryHandler->createQuery($newQuery);

            foreach ($newQuery->availableLocales as $locale) {
                $this->queryHandler->createQueryTranslation($newQuery, $locale);
            }
        }

        return $newCollection;
    }

    public function createCollectionStatus(Collection $collection, $newStatus)
    {
        $newCollection = clone $collection;
        $newCollection->status = $newStatus;

        $this->queryHandler->createCollection($newCollection);

        foreach ($newCollection->availableLocales as $locale) {
            $this->queryHandler->createCollectionTranslation($newCollection, $locale);
        }

        $collectionItems = $this->loadCollectionItems($collection);

        foreach ($collectionItems as $collectionItem) {
            $newItem = clone $collectionItem;
            $newItem->status = $newStatus;

            $this->queryHandler->addItem($newItem);
        }

        $collectionQuery = null;
        try {
            $collectionQuery = $this->loadCollectionQuery($collection);
        } catch (NotFoundException $e) {
            // Do nothing
        }

        if ($collectionQuery instanceof Query) {
            $newQuery = clone $collectionQuery;
            $newQuery->status = $newStatus;

            $this->queryHandler->createQuery($newQuery);

            foreach ($newQuery->availableLocales as $locale) {
                $this->queryHandler->createQueryTranslation($newQuery, $locale);
            }
        }

        return $newCollection;
    }

    public function deleteCollection($collectionId, $status = null)
    {
        $this->queryHandler->deleteCollectionItems($collectionId, $status);

        $queryIds = $this->queryHandler->loadCollectionQueryIds($collectionId, $status);
        $this->queryHandler->deleteQueryTranslations($queryIds, $status);
        $this->queryHandler->deleteQuery($queryIds, $status);

        $this->queryHandler->deleteCollectionTranslations($collectionId, $status);
        $this->queryHandler->deleteCollection($collectionId, $status);
    }

    public function deleteCollectionTranslation(Collection $collection, $locale)
    {
        if (!in_array($locale, $collection->availableLocales, true)) {
            throw new BadStateException('locale', 'Collection does not have the provided locale.');
        }

        if ($locale === $collection->mainLocale) {
            throw new BadStateException('locale', 'Main translation cannot be removed from the collection.');
        }

        $queryIds = $this->queryHandler->loadCollectionQueryIds($collection->id, $collection->status);
        $this->queryHandler->deleteQueryTranslations($queryIds, $collection->status, $locale);

        $this->queryHandler->deleteCollectionTranslations($collection->id, $collection->status, $locale);

        return $this->loadCollection($collection->id, $collection->status);
    }

    public function addItem(Collection $collection, ItemCreateStruct $itemCreateStruct)
    {
        $position = $this->createItemPosition($collection, $itemCreateStruct->position);

        $newItem = new Item(
            [
                'collectionId' => $collection->id,
                'position' => $position,
                'type' => $itemCreateStruct->type,
                'value' => $itemCreateStruct->value,
                'valueType' => $itemCreateStruct->valueType,
                'status' => $collection->status,
                'config' => $itemCreateStruct->config,
            ]
        );

        return $this->queryHandler->addItem($newItem);
    }

    public function updateItem(Item $item, ItemUpdateStruct $itemUpdateStruct)
    {
        $updatedItem = clone $item;

        if (is_array($itemUpdateStruct->config)) {
            $updatedItem->config = $itemUpdateStruct->config;
        }

        $this->queryHandler->updateItem($updatedItem);

        return $updatedItem;
    }

    public function moveItem(Item $item, $position)
    {
        $collection = $this->loadCollection($item->collectionId, $item->status);

        $switchWithItem = null;
        if ($item->position + 1 === $position || $item->position - 1 === $position) {
            try {
                $switchWithItem = $this->loadItemWithPosition($collection, $position);
            } catch (NotFoundException $e) {
                // Do nothing
            }
        }

        if ($switchWithItem instanceof Item) {
            // If we're moving an item to an adjacent position where an item is already
            // present, we simply switch their positions in order to minimize impact on
            // other items
            $this->switchItemPositions($item, $switchWithItem);

            return $this->loadItem($item->id, $item->status);
        }

        $movedItem = clone $item;

        if ($item->position !== $position) {
            $movedItem->position = $this->moveItemToPosition($collection, $item, $position);

            $this->queryHandler->updateItem($movedItem);
        }

        return $movedItem;
    }

    public function switchItemPositions(Item $item1, Item $item2)
    {
        if ($item1->id === $item2->id) {
            throw new BadStateException('item1', 'First and second items are the same.');
        }

        if ($item1->collectionId !== $item2->collectionId) {
            throw new BadStateException('item1', 'Positions can be switched only for items within the same collection.');
        }

        $updatedItem1 = clone $item1;
        $updatedItem2 = clone $item2;

        $updatedItem1->position = $item2->position;
        $updatedItem2->position = $item1->position;

        $this->queryHandler->updateItem($updatedItem1);
        $this->queryHandler->updateItem($updatedItem2);
    }

    public function deleteItem(Item $item)
    {
        $collection = $this->loadCollection($item->collectionId, $item->status);

        $this->queryHandler->deleteItem($item->id, $item->status);

        if (!$this->isCollectionDynamic($collection)) {
            $this->positionHelper->removePosition(
                $this->getPositionHelperItemConditions(
                    $item->collectionId,
                    $item->status
                ),
                $item->position
            );
        }
    }

    public function deleteItems(Collection $collection, $itemType = null)
    {
        $this->queryHandler->deleteItems($collection->id, $collection->status, $itemType);

        return $this->loadCollection($collection->id, $collection->status);
    }

    public function createQuery(Collection $collection, QueryCreateStruct $queryCreateStruct)
    {
        try {
            $this->loadCollectionQuery($collection);

            throw new BadStateException('collection', 'Provided collection already has a query.');
        } catch (NotFoundException $e) {
            // Do nothing
        }

        $queryParameters = [];
        foreach ($collection->availableLocales as $collectionLocale) {
            $queryParameters[$collectionLocale] = $queryCreateStruct->parameters;
        }

        $newQuery = new Query(
            [
                'collectionId' => $collection->id,
                'type' => $queryCreateStruct->type,
                'parameters' => $queryParameters,
                'status' => $collection->status,
                'isTranslatable' => $collection->isTranslatable,
                'alwaysAvailable' => $collection->alwaysAvailable,
                'mainLocale' => $collection->mainLocale,
                'availableLocales' => $collection->availableLocales,
            ]
        );

        $newQuery = $this->queryHandler->createQuery($newQuery);

        foreach ($collection->availableLocales as $collectionLocale) {
            $this->queryHandler->createQueryTranslation($newQuery, $collectionLocale);
        }

        return $newQuery;
    }

    public function updateQueryTranslation(Query $query, $locale, QueryTranslationUpdateStruct $translationUpdateStruct)
    {
        $updatedQuery = clone $query;

        if (!in_array($locale, $query->availableLocales, true)) {
            throw new BadStateException('locale', 'Query does not have the provided locale.');
        }

        if (is_array($translationUpdateStruct->parameters)) {
            $updatedQuery->parameters[$locale] = $translationUpdateStruct->parameters;
        }

        $this->queryHandler->updateQueryTranslation($updatedQuery, $locale);

        return $updatedQuery;
    }

    public function deleteCollectionQuery(Collection $collection)
    {
        $queryIds = $this->queryHandler->loadCollectionQueryIds($collection->id, $collection->status);
        $this->queryHandler->deleteQueryTranslations($queryIds, $collection->status);
        $this->queryHandler->deleteQuery($queryIds, $collection->status);
    }

    /**
     * Returns if the provided collection is a dynamic collection (i.e. if it has a query).
     *
     * @param \Netgen\BlockManager\Persistence\Values\Collection\Collection $collection
     *
     * @return bool
     */
    private function isCollectionDynamic(Collection $collection)
    {
        try {
            $this->loadCollectionQuery($collection);
        } catch (NotFoundException $e) {
            return false;
        }

        return true;
    }

    /**
     * Creates space for a new manual item by shifting positions of other items
     * below the new position.
     *
     * In case of a manual collection, the case is simple, all items below the position
     * are incremented.
     *
     * In case of a dynamic collection, the items below are incremented, but only up
     * until the first break in positions.
     *
     * @param \Netgen\BlockManager\Persistence\Values\Collection\Collection $collection
     * @param int $newPosition
     *
     * @return int
     */
    private function createItemPosition(Collection $collection, $newPosition)
    {
        if (!$this->isCollectionDynamic($collection)) {
            return $this->positionHelper->createPosition(
                $this->getPositionHelperItemConditions(
                    $collection->id,
                    $collection->status
                ),
                $newPosition
            );
        }

        return $this->incrementItemPositions($collection, $newPosition);
    }

    /**
     * Moves the item to provided position.
     *
     * In case of a manual collection, the case is simple, only positions between the old
     * and new position are ever updated.
     *
     * In case of a dynamic collection, the items below the new position are incremented,
     * but only up until the first break in positions. The positions are never decremented.
     *
     * @param \Netgen\BlockManager\Persistence\Values\Collection\Collection $collection
     * @param \Netgen\BlockManager\Persistence\Values\Collection\Item $item
     * @param int $newPosition
     *
     * @return int
     */
    private function moveItemToPosition(Collection $collection, Item $item, $newPosition)
    {
        if (!$this->isCollectionDynamic($collection)) {
            return $this->positionHelper->moveToPosition(
                $this->getPositionHelperItemConditions(
                    $collection->id,
                    $collection->status
                ),
                $item->position,
                $newPosition
            );
        }

        return $this->incrementItemPositions($collection, $newPosition, $item->position);
    }

    /**
     * Creates space for a new manual item by shifting positions of other items
     * below the new position, but only up until the first break in positions,
     * or up to $maxPosition if provided.
     *
     * @param \Netgen\BlockManager\Persistence\Values\Collection\Collection $collection
     * @param int $startPosition
     * @param int $maxPosition
     *
     * @return int
     */
    private function incrementItemPositions(Collection $collection, $startPosition, $maxPosition = null)
    {
        $items = $this->loadCollectionItems($collection);
        $endPosition = $startPosition - 1;

        foreach ($items as $item) {
            if ($item->position < $startPosition) {
                // Skip all items located before the start position,
                // we don't need to touch those.
                continue;
            }

            if ($item->position - $endPosition > 1 || $item->position === $maxPosition) {
                // Once we reach a break in positions, or if we we've come to the maximum
                // allowed position, we simply stop.
                break;
            }

            $endPosition = $item->position;
        }

        if ($endPosition < $startPosition) {
            return $startPosition;
        }

        return $this->positionHelper->createPosition(
            $this->getPositionHelperItemConditions(
                $collection->id,
                $collection->status
            ),
            $startPosition,
            $endPosition,
            true
        );
    }

    /**
     * Builds the condition array that will be used with position helper and items in collections.
     *
     * @param int|string $collectionId
     * @param int $status
     *
     * @return array
     */
    private function getPositionHelperItemConditions($collectionId, $status)
    {
        return [
            'table' => 'ngbm_collection_item',
            'column' => 'position',
            'conditions' => [
                'collection_id' => $collectionId,
                'status' => $status,
            ],
        ];
    }
}
