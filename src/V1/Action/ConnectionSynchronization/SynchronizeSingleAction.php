<?php

namespace EdgeBox\SyncCore\V1\Action\ConnectionSynchronization;

use EdgeBox\SyncCore\V1\Action\SubItemAction;
use EdgeBox\SyncCore\V1\Storage\Storage;
use EdgeBox\SyncCore\V1\SyncCoreClient;

/**
 * Class SynchronizeSingleAction.
 *
 * Trigger synchronization for a specific entity.
 *
 * @package Drupal\cms_content_sync\SyncCore\V1\Action\ConnectionSynchronization
 */
class SynchronizeSingleAction extends SubItemAction {

  /**
   * SynchronizeSingleAction constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\Storage\Storage $storage
   */
  public function __construct(Storage $storage) {
    parent::__construct($storage, 'clone', SyncCoreClient::METHOD_POST);
  }

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function isManual($set) {
    $this->arguments['manual'] = $set ? 'true' : 'false';

    return $this;
  }

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function isDependency($set) {
    $this->arguments['dependency'] = $set ? 'true' : 'false';

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function returnBoolean() {
    return TRUE;
  }

}
