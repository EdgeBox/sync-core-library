<?php

namespace SyncCore\V1\Query;

use Drupal\cms_content_sync\SyncCore\V1\SyncCoreClient;

/**
 * Class UpdateItemQuery
 * Update an existing item.
 *
 * @package Drupal\cms_content_sync\SyncCore
 */
class UpdateItemQuery extends ItemQuery {
  /**
   * @var array
   */
  protected $item = NULL;

  /**
   * @inheritdoc
   */
  public static function create($storage) {
    return new UpdateItemQuery($storage);
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
  public function getMethod() {
    return SyncCoreClient::METHOD_PUT;
  }

}
