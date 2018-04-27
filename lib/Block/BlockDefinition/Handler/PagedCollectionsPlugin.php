<?php

namespace Netgen\BlockManager\Block\BlockDefinition\Handler;

use Netgen\BlockManager\Parameters\ParameterBuilderInterface;
use Netgen\BlockManager\Parameters\ParameterType;

/**
 * Block plugin which adds options to control AJAX paging of block collections.
 */
final class PagedCollectionsPlugin extends Plugin
{
    /**
     * The list of pager types available in the plugin.
     *
     * @var array
     */
    private $pagerTypes = [];

    /**
     * @var array
     */
    private $defaultGroups = [];

    public function __construct(array $pagerTypes = [], array $defaultGroups = [])
    {
        $this->pagerTypes = array_flip($pagerTypes);
        $this->defaultGroups = $defaultGroups;
    }

    public static function getExtendedHandler()
    {
        return PagedCollectionsBlockInterface::class;
    }

    public function buildParameters(ParameterBuilderInterface $builder)
    {
        $builder->add(
            'paged_collections:enabled',
            ParameterType\Compound\BooleanType::class,
            [
                'label' => 'block.plugin.paged_collections.enabled',
                'groups' => $this->defaultGroups,
            ]
        );

        $builder->get('paged_collections:enabled')->add(
            'paged_collections:type',
            ParameterType\ChoiceType::class,
            [
                'options' => $this->pagerTypes,
                'label' => 'block.plugin.paged_collections.type',
                'groups' => $this->defaultGroups,
            ]
        );

        $builder->get('paged_collections:enabled')->add(
            'paged_collections:max_pages',
            ParameterType\IntegerType::class,
            [
                'min' => 1,
                'label' => 'block.plugin.paged_collections.max_pages',
                'groups' => $this->defaultGroups,
            ]
        );

        $builder->get('paged_collections:enabled')->add(
            'paged_collections:ajax_first',
            ParameterType\BooleanType::class,
            [
                'label' => 'block.plugin.paged_collections.ajax_first',
                'groups' => $this->defaultGroups,
            ]
        );
    }
}
