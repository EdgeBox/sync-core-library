<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

interface IDefineFlow extends IBatchOperation
{
    /**
     * @return IDefinePoolForFlow
     */
    public function usePool(string $pool_id);
}
