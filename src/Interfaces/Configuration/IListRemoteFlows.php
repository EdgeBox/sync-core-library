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
     * @return IRemoteFlowListItem[] the properties the remote site saved for
     *                               this Flow
     *
     * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
     */
    public function execute();
}
