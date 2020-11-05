<?php

namespace EdgeBox\SyncCore\V1\Action\ConnectionSynchronization;

use Drupal\cms_content_sync\SyncCore\V1\Action\ItemAction;
use Drupal\cms_content_sync\SyncCore\V1\Storage\Storage;
use Drupal\cms_content_sync\SyncCore\V1\SyncCoreClient;

/**
 * Class SynchronizeAllAction.
 *
 * Trigger synchronization for a specific entity.
 *
 * @package Drupal\cms_content_sync\SyncCore\V1\Action\ConnectionSynchronization
 */
class SynchronizeAllAction extends ItemAction {

  /**
   * SynchronizeAllAction constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\Storage\Storage $storage
   */
  public function __construct(Storage $storage) {
    parent::__construct($storage, 'synchronize', SyncCoreClient::METHOD_POST);

    $this->arguments['update_all'] = 'true';
  }

  /**
   * @param bool $set
   * @return $this
   */
  public function force($set) {
    $this->arguments['force'] = $set ? 'true' : 'false';

    return $this;
  }

}
