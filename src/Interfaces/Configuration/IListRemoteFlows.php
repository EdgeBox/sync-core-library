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
     * @throws SyncCoreException
     *
     * @return IRemoteFlowListItem[] the properties the remote site saved for
     *                               this Flow
     */
    public function execute();
}
