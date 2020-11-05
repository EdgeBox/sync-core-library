<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

/**
 *
 */
interface IFlowPullConfiguration extends IBatchOperation {

  /**
   * Only apply syndication if one of the given entities is referenced. This
   * allows users to syndicate content based on simple tagging.
   *
   * @param string $property
   * @param string[] $allowed_entity_ids
   *
   * @return $this
   */
  public function ifTaggedWith($property, $allowed_entity_ids);

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function manually($set);

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function asDependency($set);

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function pullDeletions($set);

  /**
   * @param string $flow_id
   *
   * @return IFlowPullConfiguration
   */
  public function configureOverride($flow_id);

}
