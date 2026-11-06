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
     * Name the item by its other ID as well, so the request carries both its
     * UUID and its unique ID: created with the item's UUID, this is its unique
     * ID, and the other way round. Calling it again replaces that other ID.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException the ID is empty, `.` or `..`
     */
    public function alsoKnownAs(string $shared_entity_id);

    /**
     * @return $this
     *
     * @throws SyncCoreException
     */
    public function execute();

    /**
     * Send the pull in a single attempt, never again after a connection
     * failure: the first attempt may have reached the Sync Core, and through a
     * Flow that pulls copies a second one creates a second copy.
     *
     * @param bool $configuration_access whether to send it with the site's
     *                                   configuration scope, as the pull
     *                                   dashboard does for an administrator,
     *                                   rather than its content scope
     *
     * @return array<string, mixed> the task the Sync Core created, as the JSON
     *                              object it sent; a field it leaves out is
     *                              missing from the array
     *
     * @throws SyncCoreException also when the answer is not a JSON object
     */
    public function executeOnce(bool $configuration_access);

    /**
     * @return null|IPullDashboardSearchResultItem
     */
    public function getPullDashboardSearchResultItem();

    /**
     * @return null|string the update ID to query for the status
     */
    public function getId();
}
