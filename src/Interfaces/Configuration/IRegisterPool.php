<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Interfaces\IBatchOperation;

interface IRegisterPool extends IBatchOperation
{
    /**
     * @return null
     *
     * @throws SyncCoreException
     */
    public function execute();
}
