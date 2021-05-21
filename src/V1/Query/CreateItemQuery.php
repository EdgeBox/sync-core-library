<?php

namespace EdgeBox\SyncCore\V1\Query;

use EdgeBox\SyncCore\V1\SyncCoreClient;

/**
 * Class CreateItemQuery
 * Create a new item.
 *
 * @package Drupal\cms_content_sync\SyncCore
 */
class CreateItemQuery extends ItemQuery {

  /**
   * @var array|null
   */
  protected $item = NULL;

  /**
   * @inheritdoc
   */
  public static function create($storage) {
    return new CreateItemQuery($storage);
  }

  /**
   * @inheritdoc
   */
  public function setAsDependency($set) {
    if ($set) {
      $this->arguments['is_dependency'] = 'true';
    }
    else {
      unset($this->arguments['is_dependency']);
    }

    return $this;
  }

  /**
   * @param array $item
   *
   * @return $this
   */
  public function setItem($item) {
    $this->item = $item;

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getBody() {
    return $this->item;
  }

  /**
   * @inheritdoc
   */
  public function getPath() {
    return $this->storage->getPath();
  }

  /**
   * @inheritdoc
   */
  public function getMethod() {
    return SyncCoreClient::METHOD_POST;
  }

}
