<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

/**
 *
 */
interface IListRemoteFlows {

  /**
   * @param string $pool_id
   *
   * @return $this
   */
  public function thatUsePool($pool_id);

  /**
   * @return IRemoteFlowListItem[] The properties the remote site saved for
   *   this Flow.
   *
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException
   */
  public function execute();

}
