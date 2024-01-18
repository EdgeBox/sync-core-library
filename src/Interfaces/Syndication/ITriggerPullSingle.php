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
     * @throws SyncCoreException
     *
     * @return $this
     */
    public function execute();

    /**
     * @return null|IPullDashboardSearchResultItem
     */
    public function getPullDashboardSearchResultItem();

    /**
     * @return null|string the update ID to query for the status
     */
    public function getId();
}
