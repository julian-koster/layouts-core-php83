<?php

namespace Netgen\BlockManager\Collection\QueryType;

use Netgen\BlockManager\API\Values\Collection\Query;
use Netgen\BlockManager\Parameters\ParameterBuilderInterface;

interface QueryTypeHandlerInterface
{
    const GROUP_ADVANCED = 'advanced';

    /**
     * Builds the parameters by using provided parameter builder.
     *
     * @param \Netgen\BlockManager\Parameters\ParameterBuilderInterface $builder
     */
    public function buildParameters(ParameterBuilderInterface $builder);

    /**
     * Returns the values from the query.
     *
     * @param \Netgen\BlockManager\API\Values\Collection\Query $query
     * @param int $offset
     * @param int $limit
     *
     * @return mixed[]
     */
    public function getValues(Query $query, $offset = 0, $limit = null);

    /**
     * Returns the value count from the query.
     *
     * @param \Netgen\BlockManager\API\Values\Collection\Query $query
     *
     * @return int
     */
    public function getCount(Query $query);

    /**
     * Returns the limit internal to this query.
     *
     * @param \Netgen\BlockManager\API\Values\Collection\Query $query
     *
     * @return int
     */
    public function getInternalLimit(Query $query);

    /**
     * Returns if the provided query is dependent on a context, i.e. current request.
     *
     * @param \Netgen\BlockManager\API\Values\Collection\Query $query
     *
     * @return bool
     */
    public function isContextual(Query $query);
}
