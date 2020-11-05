<?php

namespace SyncCore\Interfaces\Configuration;

use Drupal\cms_content_sync\SyncCore\Interfaces\IBatchOperation;

/**
 *
 */
interface IDefineFlow extends IBatchOperation {

  /**
   * @param string $pool_id
   *
   * @return IDefinePoolForFlow
   */
  public function usePool($pool_id);

}
