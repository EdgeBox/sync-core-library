<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

interface ITriggerPullSingle
{
    /**
     * @param string $pool_id
     *
     * @return $this
     */
    public function fromPool($pool_id);

    /**
     * @param bool $set
     *
     * @return $this
     */
    public function manually($set);

    /**
     * @param bool $set
     *
     * @return $this
     */
    public function asDependency($set);

    /**
     * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
     *
     * @return $this
     */
    public function execute();

    /**
     * @return IPullDashboardSearchResultItem
     */
    public function getPullDashboardSearchResultItem();
}
