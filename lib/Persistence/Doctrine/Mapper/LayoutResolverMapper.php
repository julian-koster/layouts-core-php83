<?php

namespace Netgen\BlockManager\Persistence\Doctrine\Mapper;

use Netgen\BlockManager\Persistence\Values\LayoutResolver\Rule;
use Netgen\BlockManager\Persistence\Values\LayoutResolver\Target;
use Netgen\BlockManager\Persistence\Values\LayoutResolver\Condition;

class LayoutResolverMapper
{
    /**
     * Maps data from database to rule value objects.
     *
     * @param array $data
     *
     * @return \Netgen\BlockManager\Persistence\Values\LayoutResolver\Rule[]
     */
    public function mapRules(array $data = array())
    {
        $rules = array();

        foreach ($data as $dataItem) {
            $rules[] = new Rule(
                array(
                    'id' => (int)$dataItem['id'],
                    'status' => (int)$dataItem['status'],
                    'layoutId' => (int)$dataItem['layout_id'],
                    'enabled' => (bool)$dataItem['enabled'],
                    'priority' => (int)$dataItem['priority'],
                    'comment' => $dataItem['comment'],
                )
            );
        }

        return $rules;
    }

    /**
     * Maps data from database to target value objects.
     *
     * @param array $data
     *
     * @return \Netgen\BlockManager\Persistence\Values\LayoutResolver\Target[]
     */
    public function mapTargets(array $data = array())
    {
        $targets = array();

        foreach ($data as $dataItem) {
            $targets[] = new Target(
                array(
                    'id' => (int)$dataItem['id'],
                    'status' => (int)$dataItem['status'],
                    'ruleId' => (int)$dataItem['rule_id'],
                    'identifier' => $dataItem['identifier'],
                    'value' => $dataItem['value'],
                )
            );
        }

        return $targets;
    }

    /**
     * Maps data from database to condition value objects.
     *
     * @param array $data
     *
     * @return \Netgen\BlockManager\Persistence\Values\LayoutResolver\Condition[]
     */
    public function mapConditions(array $data = array())
    {
        $conditions = array();

        foreach ($data as $dataItem) {
            $conditions[] = new Condition(
                array(
                    'id' => (int)$dataItem['id'],
                    'status' => (int)$dataItem['status'],
                    'ruleId' => (int)$dataItem['rule_id'],
                    'identifier' => $dataItem['identifier'],
                    'value' => json_decode($dataItem['value'], true),
                )
            );
        }

        return $conditions;
    }
}
