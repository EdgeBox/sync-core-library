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

    /**
     * Serialize the operation to send to the Sync Core through the site
     * config REST interfact.
     *
     * @throws SyncCoreException
     *
     * @return mixed
     */
    public function getDto();
}
