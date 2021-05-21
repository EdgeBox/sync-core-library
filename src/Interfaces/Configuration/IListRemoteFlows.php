<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Exception\SyncCoreException;

interface IListRemoteFlows
{
    /**
     * @return $this
     */
    public function thatUsePool(string $pool_id);

    /**
     * @return IRemoteFlowListItem[] the properties the remote site saved for
     *                               this Flow
     *
     * @throws SyncCoreException
     */
    public function execute();
}
