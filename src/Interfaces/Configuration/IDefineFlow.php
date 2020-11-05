<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

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
