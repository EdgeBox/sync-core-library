<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCoreInterfaces\IBatchOperation;

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
