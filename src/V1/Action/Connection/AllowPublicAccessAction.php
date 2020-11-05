<?php

namespace SyncCore\V1\Action\Connection;

use Drupal\cms_content_sync\SyncCore\V1\Action\ItemAction;
use Drupal\cms_content_sync\SyncCore\V1\Storage\PreviewEntityStorage;
use Drupal\cms_content_sync\SyncCore\V1\Storage\Storage;
use Drupal\cms_content_sync\SyncCore\V1\SyncCoreClient;

/**
 * Class AllowPublicAccessAction.
 *
 * Set whether or not to allow direct Sync Core communication.
 *
 * @package Drupal\cms_content_sync\SyncCore\V1\Action\Connection
 */
class AllowPublicAccessAction extends ItemAction {

  /**
   * @var bool
   */
  protected $value = NULL;

  /**
   * CloneAction constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\Storage\Storage $storage
   */
  public function __construct(Storage $storage) {
    parent::__construct($storage, '', SyncCoreClient::METHOD_POST);
  }

  /**
   * @inheritdoc
   */
  public function getPath() {
    return '/' . PreviewEntityStorage::EXTERNAL_PREVIEW_PATH . '/_allowPublicAccess';
  }

  /**
   * @param bool $set
   * @return AllowPublicAccessAction
   */
  public function setAllowPublicAccess($set) {
    $this->value = $set;

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getBody() {
    return $this->value;
  }

  /**
   * @inheritdoc
   */
  public function returnBoolean() {
    return 'success';
  }

}
