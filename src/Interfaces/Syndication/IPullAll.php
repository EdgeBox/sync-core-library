<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

use EdgeBox\SyncCore\Interfaces\IProgress;

/**
 *
 */
interface IPullAll extends IProgress {

  /**
   * @param string $pool_id
   *
   * @return $this
   */
  public function fromPool($pool_id);

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function force($set);

  /**
   * @return string
   */
  public function getTypeMachineName();

  /**
   * @return string
   */
  public function getBundleMachineName();

  /**
   * @return string
   */
  public function getPoolMachineName();

  /**
   * @return $this
   *
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException
   */
  public function execute();

  /**
   * @param bool $fromCache
   *
   * @return int
   */
  public function progress($fromCache = FALSE);

}
