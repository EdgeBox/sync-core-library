<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCoreInterfaces\IBatchOperation;

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
