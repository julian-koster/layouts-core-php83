<?php

declare(strict_types=1);

namespace Netgen\Bundle\BlockManagerBundle\Tests\DependencyInjection\ConfigurationNode;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration;
use Netgen\Bundle\BlockManagerBundle\DependencyInjection\NetgenBlockManagerExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class LayoutTypeNodeTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::__construct
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getNodes
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeSettings(): void
    {
        $config = [
            [
                'layout_types' => [
                    'layout' => [
                        'name' => 'layout',
                        'icon' => '/icon.svg',
                        'zones' => [
                            'zone' => [
                                'name' => 'zone',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $expectedConfig = [
            'layout_types' => [
                'layout' => [
                    'name' => 'layout',
                    'icon' => '/icon.svg',
                    'enabled' => true,
                    'zones' => [
                        'zone' => [
                            'name' => 'zone',
                            'allowed_block_definitions' => [],
                        ],
                    ],
                ],
            ],
        ];

        self::assertProcessedConfigurationEquals(
            $config,
            $expectedConfig,
            'layout_types'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getNodes
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeSettingsWithNoIcon(): void
    {
        $config = [
            [
                'layout_types' => [
                    'layout' => [],
                ],
            ],
        ];

        $expectedConfig = [
            'layout_types' => [
                'layout' => [
                    'icon' => null,
                ],
            ],
        ];

        self::assertProcessedConfigurationEquals(
            $config,
            $expectedConfig,
            'layout_types.*.icon'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getNodes
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeSettingsWithNullIcon(): void
    {
        $config = [
            [
                'layout_types' => [
                    'layout' => [
                        'icon' => null,
                    ],
                ],
            ],
        ];

        $expectedConfig = [
            'layout_types' => [
                'layout' => [
                    'icon' => null,
                ],
            ],
        ];

        self::assertProcessedConfigurationEquals(
            $config,
            $expectedConfig,
            'layout_types.*.icon'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::__construct
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getNodes
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeSettingsNoZonesMerge(): void
    {
        $config = [
            [
                'layout_types' => [
                    'layout' => [
                        'zones' => [
                            'left' => [
                                'name' => 'Left',
                            ],
                            'right' => [
                                'name' => 'Right',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'layout_types' => [
                    'layout' => [
                        'zones' => [
                            'top' => [
                                'name' => 'Top',
                            ],
                            'bottom' => [
                                'name' => 'Bottom',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $expectedConfig = [
            'layout_types' => [
                'layout' => [
                    'zones' => [
                        'top' => [
                            'name' => 'Top',
                            'allowed_block_definitions' => [],
                        ],
                        'bottom' => [
                            'name' => 'Bottom',
                            'allowed_block_definitions' => [],
                        ],
                    ],
                ],
            ],
        ];

        self::assertProcessedConfigurationEquals(
            $config,
            $expectedConfig,
            'layout_types.*.zones'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::__construct
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getNodes
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeSettingsWithAllowedBlockDefinitions(): void
    {
        $config = [
            [
                'layout_types' => [
                    'layout' => [
                        'zones' => [
                            'zone' => [
                                'allowed_block_definitions' => ['title', 'text'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $expectedConfig = [
            'layout_types' => [
                'layout' => [
                    'zones' => [
                        'zone' => [
                            'allowed_block_definitions' => ['title', 'text'],
                        ],
                    ],
                ],
            ],
        ];

        self::assertProcessedConfigurationEquals(
            $config,
            $expectedConfig,
            'layout_types.*.zones.*.allowed_block_definitions'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::__construct
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getNodes
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeSettingsWithNonUniqueAllowedBlockDefinitions(): void
    {
        $config = [
            [
                'layout_types' => [
                    'layout' => [
                        'zones' => [
                            'zone' => [
                                'allowed_block_definitions' => ['title', 'text', 'title'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $expectedConfig = [
            'layout_types' => [
                'layout' => [
                    'zones' => [
                        'zone' => [
                            'allowed_block_definitions' => ['title', 'text'],
                        ],
                    ],
                ],
            ],
        ];

        self::assertProcessedConfigurationEquals(
            $config,
            $expectedConfig,
            'layout_types.*.zones.*.allowed_block_definitions'
        );
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeSettingsWithEmptyLayouts(): void
    {
        $config = ['layout_types' => []];
        self::assertConfigurationIsInvalid([$config]);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeSettingsWithNoName(): void
    {
        $config = ['layout_types' => ['layout' => []]];
        self::assertConfigurationIsInvalid([$config]);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeSettingsWithNoZones(): void
    {
        $config = [
            'layout_types' => [
                'layout' => [
                    'name' => 'layout',
                ],
            ],
        ];

        self::assertConfigurationIsInvalid([$config]);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeWithEmptyIcon(): void
    {
        $config = [
            'layout_types' => [
                'layout' => [
                    'name' => 'Layout',
                    'icon' => '',
                ],
            ],
        ];

        self::assertConfigurationIsInvalid([$config], 'Icon path needs to be a non empty string or null.');
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeWithNonStringIcon(): void
    {
        $config = [
            'layout_types' => [
                'layout' => [
                    'name' => 'Layout',
                    'icon' => 42,
                ],
            ],
        ];

        self::assertConfigurationIsInvalid([$config], 'Icon path needs to be a non empty string or null.');
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeSettingsWithEmptyZones(): void
    {
        $config = [
            'layout_types' => [
                'layout' => [
                    'name' => 'layout',
                    'zones' => [],
                ],
            ],
        ];

        self::assertConfigurationIsInvalid([$config]);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeSettingsWithNoZoneName(): void
    {
        $config = [
            'layout_types' => [
                'layout' => [
                    'name' => 'layout',
                    'zones' => [],
                ],
            ],
        ];

        self::assertConfigurationIsInvalid([$config]);
    }

    /**
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     * @covers \Netgen\Bundle\BlockManagerBundle\DependencyInjection\ConfigurationNode\LayoutTypeNode::getConfigurationNode
     */
    public function testLayoutTypeSettingsWithEmptyAllowedBlockDefinitions(): void
    {
        $config = [
            'layout_types' => [
                'layout' => [
                    'name' => 'layout',
                    'zones' => [
                        'name' => 'zone',
                        'allowed_block_definitions' => [],
                    ],
                ],
            ],
        ];

        self::assertConfigurationIsInvalid([$config]);
    }

    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration(new NetgenBlockManagerExtension());
    }
}
