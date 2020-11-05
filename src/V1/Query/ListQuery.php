<?php

namespace SyncCore\V1\Query;

use Drupal\cms_content_sync\SyncCore\V1\Query\Condition\ParentCondition;
use Drupal\cms_content_sync\SyncCore\V1\Query\Result\ListResult;

/**
 * Class ListQuery
 * Retrieve a list of remote entities with optional filters.
 *
 * @package Drupal\cms_content_sync\SyncCore
 */
class ListQuery extends StorageQuery {

  /**
   * @var string ORDER_ASCENDING
   * Sort the entities by the given field, starting with the lowest value.
   */
  const ORDER_ASCENDING = 'ASC';

  /**
   * @var string ORDER_DESCENDING
   * Sort the entities by the given field, starting with the highest value.
   */
  const ORDER_DESCENDING = 'DESC';

  /**
   * Set how many items should be returned per request (page).
   *
   * @param int $count
   *
   * @return $this
   */
  public function setItemsPerPage($count) {
    if ($count === NULL) {
      unset($this->arguments['items_per_page']);
    }
    else {
      $this->arguments['items_per_page'] = $count;
    }

    return $this;
  }

  /**
   * Get the value last set by ->setItemsPerPage().
   *
   * @return int
   */
  public function getItemsPerPage() {
    return isset($this->arguments['items_per_page']) ? $this->arguments['items_per_page'] : NULL;
  }

  /**
   * Set which page to return.
   *
   * @param int $page
   *
   * @return $this
   */
  public function setPage($page) {
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
  public function orderBy($field, $direction = self::ORDER_ASCENDING) {
    if ($direction != self::ORDER_ASCENDING && $direction != self::ORDER_DESCENDING) {
      throw new \Exception('Unknown order direction ', $direction);
    }

    $this->arguments['order_by'][$field] = $direction;

    return $this;
  }

  /**
   * Get details of each entity instead of ID and name only.
   *
   * @return $this
   */
  public function getDetails() {
    $this->arguments['property_list'] = 'details';

    return $this;
  }

  /**
   * Apply the given condition to the list before returning it.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\Query\Condition\Condition|Condition[] $condition
   *
   * @return $this
   */
  public function setCondition($condition) {
    if (is_array($condition)) {
      if (count($condition) > 1) {
        $condition = ParentCondition::all($condition);
      }
      else {
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
  public function toArray() {
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
   * @inheritdoc
   */
  public static function create($storage) {
    return new ListQuery($storage);
  }

  /**
   * @inheritdoc
   */
  public function getPath() {
    return $this->storage->getPath();
  }

  /**
   * @return \Drupal\cms_content_sync\SyncCore\V1\Query\Result\ListResult
   */
  public function execute() {
    return new ListResult($this);
  }

}
