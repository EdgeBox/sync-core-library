<?php

namespace EdgeBox\SyncCore\Interfaces;

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
   * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
   */
  public function execute();

}
