<?php

namespace Netgen\BlockManager\Item;

use Netgen\BlockManager\Exception\InvalidInterfaceException;
use Netgen\BlockManager\Exception\Item\ValueException;

final class ItemBuilder implements ItemBuilderInterface
{
    /**
     * @var \Netgen\BlockManager\Item\ValueConverterInterface[]
     */
    private $valueConverters = [];

    /**
     * @param \Netgen\BlockManager\Item\ValueConverterInterface[] $valueConverters
     */
    public function __construct(array $valueConverters = [])
    {
        foreach ($valueConverters as $valueConverter) {
            if (!$valueConverter instanceof ValueConverterInterface) {
                throw new InvalidInterfaceException(
                    'Value converter',
                    get_class($valueConverter),
                    ValueConverterInterface::class
                );
            }
        }

        $this->valueConverters = $valueConverters;
    }

    public function build($object)
    {
        foreach ($this->valueConverters as $valueConverter) {
            if (!$valueConverter->supports($object)) {
                continue;
            }

            $value = new Item(
                [
                    'value' => $valueConverter->getId($object),
                    'remoteId' => $valueConverter->getRemoteId($object),
                    'valueType' => $valueConverter->getValueType($object),
                    'name' => $valueConverter->getName($object),
                    'isVisible' => $valueConverter->getIsVisible($object),
                    'object' => $valueConverter->getObject($object),
                ]
            );

            return $value;
        }

        throw ValueException::noValueConverter(
            is_object($object) ? get_class($object) : gettype($object)
        );
    }
}
