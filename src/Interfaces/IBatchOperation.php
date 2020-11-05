<?php

namespace SyncCore\Interfaces;

/**
 *
 */
interface IBatchOperation {

  /**
   * @param IBatch $batch
   *
   * @return mixed
   */
  public function addToBatch($batch);

  /**
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException
   */
  public function execute();

}
