<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use Drupal\cms_content_sync\SyncCore\Interfaces\IBatchOperation;

/**
 *
 */
interface IDefinePoolForFlow extends IBatchOperation {

  /**
   * @param IDefineEntityType $entity_type
   *
   * @return $this
   */
  public function useEntityType($entity_type);

  /**
   * @param IDefineEntityType $entity_type
   *
   * @return $this
   */
  public function enablePreview($entity_type);

  /**
   * @param IDefineEntityType $entity_type
   *
   * @return $this
   */
  public function enablePush($entity_type);

  /**
   * @param IDefineEntityType $entity_type
   *
   * @return IFlowPullConfiguration
   */
  public function enablePull($entity_type);

}
