<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

interface IFlowPushConfiguration extends IBatchOperation
{
    /**
     * @return $this
     */
    public function pushDeletions(bool $set);

    /**
     * @return $this
     */
    public function manually(bool $set);

    /**
     * @return $this
     */
    public function asDependency(bool $set);
}
