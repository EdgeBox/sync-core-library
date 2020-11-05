<?php

namespace EdgeBox\SyncCore\V1\Query;

use EdgeBox\SyncCore\V1\Query\Result\ItemResult;

/**
 * Class ItemQuery
 * Get an individual item by ID.
 *
 * @package Drupal\cms_content_sync\SyncCore
 */
class ItemQuery extends StorageQuery {

  /**
   * @var string
   */
  protected $entityId;

  /**
   * @param string $id
   *
   * @return $this
   */
  public function setEntityId($id) {
    $this->entityId = $id;

    return $this;
  }

  /**
   * @inheritdoc
   */
  public static function create($storage) {
    return new ItemQuery($storage);
  }

  /**
   * @inheritdoc
   */
  public function getPath() {
    return $this->storage->getPath() . '/' . $this->entityId;
  }

  /**
   * @inheritdoc
   *
   * @return \Drupal\cms_content_sync\SyncCore\V1\Query\Result\ItemResult
   */
  public function execute() {
    $result = new ItemResult($this);

    $result->execute();

    return $result;
  }

}
