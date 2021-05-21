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
     * @return $this
     *
     * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
     */
    public function execute();

    /**
     * @return IPullDashboardSearchResultItem|null
     */
    public function getPullDashboardSearchResultItem();
}
