<?php

namespace EdgeBox\SyncCore\Interfaces;

use EdgeBox\SyncCore\Exception\SyncCoreException;

interface IBatchOperation
{
    /**
     * @return mixed
     */
    public function addToBatch(IBatch $batch);

    /**
     * @throws SyncCoreException
     */
    public function execute();
}
