<?php

namespace Netgen\BlockManager\Layout\Type;

final class LayoutTypeFactory
{
    /**
     * Builds the layout type.
     *
     * @param string $identifier
     * @param array $config
     *
     * @return \Netgen\BlockManager\Layout\Type\LayoutType
     */
    public static function buildLayoutType($identifier, array $config)
    {
        $zones = [];

        foreach ($config['zones'] as $zoneIdentifier => $zoneConfig) {
            $zones[$zoneIdentifier] = new Zone(
                [
                    'identifier' => $zoneIdentifier,
                    'name' => $zoneConfig['name'],
                    'allowedBlockDefinitions' => $zoneConfig['allowed_block_definitions'],
                ]
            );
        }

        return new LayoutType(
            [
                'identifier' => $identifier,
                'isEnabled' => $config['enabled'],
                'name' => $config['name'],
                'icon' => $config['icon'],
                'zones' => $zones,
            ]
        );
    }
}
