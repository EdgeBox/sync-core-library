<?php

namespace EdgeBox\SyncCore\V1\Action;

use EdgeBox\SyncCore\V1\Query\ItemQuery;
use EdgeBox\SyncCore\V1\Storage\Storage;

/**
 * Class ItemAction.
 *
 * Execute an action for a specific entity.
 *
 * @package Drupal\cms_content_sync\SyncCore
 */
class ItemAction extends ItemQuery {

  protected $actionPath = NULL;

  protected $method = NULL;

  /**
   * ItemAction constructor.
   *
   * @param \EdgeBox\SyncCore\V1\Storage\Storage $storage
   * @param string $actionPath
   * @param string $method
   */
  public function __construct(Storage $storage, $actionPath, $method) {
    parent::__construct($storage);

    $this->actionPath = $actionPath;
    $this->method = $method;
  }

  /**
   * @inheritdoc
   */
  public function getPath() {
    return parent::getPath() . '/' . $this->actionPath;
  }

  /**
   * @inheritdoc
   */
  public function getMethod() {
    return $this->method;
  }

}
