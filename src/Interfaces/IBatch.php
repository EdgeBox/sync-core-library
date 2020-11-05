<?php

namespace EdgeBox\SyncCore\Interfaces;

/**
 *
 */
interface IBatch {

  /**
   * @param IBatchOperation $operation
   *
   * @return $this
   */
  public function add($operation);

  /**
   * @return int
   */
  public function count();

  /**
   * @param int $index
   *
   * @return IBatchOperation
   */
  public function get($index);

  /**
   * Convenience method to call ->execute() for all indices until ->count().
   *
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException
   */
  public function executeAll();

  /**
   * Can only be used for batch operations using the same Sync Core!
   *
   * @param IBatch $other
   */
  public function prepend($other);

}
