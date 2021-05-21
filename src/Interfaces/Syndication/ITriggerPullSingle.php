<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

use EdgeBox\SyncCore\Exception\SyncCoreException;

interface ITriggerPullSingle
{
    /**
     * @return $this
     */
    public function fromPool(string $pool_id);

    /**
     * @return $this
     */
    public function manually(bool $set);

    /**
     * @return $this
     */
    public function asDependency(bool $set);

    /**
     * @return $this
     *
     * @throws SyncCoreException
     */
    public function execute();

    /**
     * @return IPullDashboardSearchResultItem|null
     */
    public function getPullDashboardSearchResultItem();
}
