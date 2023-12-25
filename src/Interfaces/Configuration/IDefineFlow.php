<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

interface IDefineFlow extends IBatchOperation
{
    /**
     * @return IDefinePoolForFlow
     */
    public function usePool(string $pool_id);

    /**
     * Define whether the Flow is active or not.
     *
     * @param null|bool $set
     *
     * @return bool
     */
    public function isActive($set = null);
}
