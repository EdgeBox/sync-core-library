<?php

namespace SyncCore\Interfaces;

/**
 *
 */
interface IReportingService {

  const LOG_LEVEL_ERROR = 'error';

  const LOG_LEVEL_WARNING = 'warn';

  /**
   * @param string[]|string|null $level
   *   See self::LOG_LEVEL*.
   *
   * @return null|array
   */
  public function getLog($level = NULL);

  /**
   * @return array[
   *   'version' => '...'
   *   'usage' => [
   *     'today' => [
   *       'entitiesPushedFromSites' => ...
   *       'rootEntitiesPushedFromSites' => ...
   *       'entitiesPulledBySites' => ...
   *       'rootEntitiesPulledBySites' => ...
   *     ]
   *   ]
   *   ]
   *
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException
   */
  public function getStatus();

}
