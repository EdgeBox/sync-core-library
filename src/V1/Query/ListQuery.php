<?php

namespace EdgeBox\SyncCore\V1\Query;

use EdgeBox\SyncCore\Exception\InternalContentSyncError;
use EdgeBox\SyncCore\V1\Query\Condition\Condition;
use EdgeBox\SyncCore\V1\Query\Condition\ParentCondition;
use EdgeBox\SyncCore\V1\Query\Result\ListResult;

/**
 * Class ListQuery
 * Retrieve a list of remote entities with optional filters.
 */
class ListQuery extends StorageQuery
{
    /**
     * @var string ORDER_ASCENDING
     *             Sort the entities by the given field, starting with the lowest value
     */
    public const ORDER_ASCENDING = 'ASC';

    /**
     * @var string ORDER_DESCENDING
     *             Sort the entities by the given field, starting with the highest value
     */
    public const ORDER_DESCENDING = 'DESC';

    /**
     * {@inheritdoc}
     */
    public static function create($storage)
    {
        return new ListQuery($storage);
    }

    /**
     * Set how many items should be returned per request (page).
     *
     * @return $this
     */
    public function setItemsPerPage(?int $count)
    {
        if (null === $count) {
            unset($this->arguments['items_per_page']);
        } else {
            $this->arguments['items_per_page'] = $count;
        }

        return $this;
    }

    /**
     * Get the value last set by ->setItemsPerPage().
     *
     * @return int
     */
    public function getItemsPerPage()
    {
        return isset($this->arguments['items_per_page']) ? $this->arguments['items_per_page'] : null;
    }

    /**
     * Set which page to return.
     *
     * @param int $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        $this->arguments['page'] = $page;

        return $this;
    }

    /**
     * Order by the given field in the given order. You can specify hierarchical
     * sorting by calling this function multiple times.
     *
     * @param string $field
     * @param string $direction
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function orderBy($field, $direction = self::ORDER_ASCENDING)
    {
        if (self::ORDER_ASCENDING != $direction && self::ORDER_DESCENDING != $direction) {
            throw new InternalContentSyncError('Unknown order direction '.$direction);
        }

        $this->arguments['order_by'][$field] = $direction;

        return $this;
    }

    /**
     * Get details of each entity instead of ID and name only.
     *
     * @return $this
     */
    public function getDetails()
    {
        $this->arguments['property_list'] = 'details';

        return $this;
    }

    /**
     * Apply the given condition to the list before returning it.
     *
     * @param Condition|Condition[] $condition
     *
     * @return $this
     */
    public function setCondition($condition)
    {
        if (is_array($condition)) {
            if (count($condition) > 1) {
                $condition = ParentCondition::all($condition);
            } else {
                $condition = $condition[0];
            }
        }

        $this->arguments['condition'] = $condition;

        return $this;
    }

    /**
     * Get the arguments stored.
     *
     * @return array
     */
    public function toArray()
    {
        $result = $this->arguments;

        if (isset($result['condition'])) {
            $result['condition'] = $result['condition']->serialize();
        }

        if (isset($result['order_by'])) {
            $result['order_by'] = json_encode($result['order_by']);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->storage->getPath();
    }

    /**
     * @return \EdgeBox\SyncCore\V1\Query\Result\ListResult
     */
    public function execute()
    {
        return new ListResult($this);
    }
}
