<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

interface IRegisterPool extends IBatchOperation
{
    /**
     * @return null
     *
     * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
     */
    public function execute();
}
