<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

interface IListRemoteFlows
{
    /**
     * @param string $pool_id
     *
     * @return $this
     */
    public function thatUsePool($pool_id);

    /**
     * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
     *
     * @return IRemoteFlowListItem[] the properties the remote site saved for
     *                               this Flow
     */
    public function execute();
}
