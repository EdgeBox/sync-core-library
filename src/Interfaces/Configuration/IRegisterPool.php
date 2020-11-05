<?php

namespace SyncCore\Interfaces\Configuration;

use Drupal\cms_content_sync\SyncCore\Interfaces\IBatchOperation;

/**
 *
 */
interface IRegisterPool extends IBatchOperation {

  /**
   * @return null
   *
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException
   */
  public function execute();

}
