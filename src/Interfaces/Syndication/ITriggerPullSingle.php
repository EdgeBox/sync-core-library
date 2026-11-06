<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Exception\TimeoutException;

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
     * Name the item by its UUID. The ID the pull was created with is sorted
     * into a UUID or a unique ID by its shape; this sets the UUID whatever its
     * shape, and takes it back out of the unique ID when it was sorted there.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException the UUID is empty, `.`, `..` or `0`
     */
    public function withUuid(string $uuid);

    /**
     * Name the item by its unique ID, the ID a site shares in place of a UUID.
     * As {@see withUuid()}, this sets the unique ID whatever its shape, and
     * takes it back out of the UUID when the ID the pull was created with was
     * sorted there.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException the unique ID is empty, `.`, `..` or `0`
     */
    public function withUniqueId(string $unique_id);

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
     * @throws TimeoutException  the Sync Core did not answer; the pull may
     *                           have reached it, so it is never sent again
     * @throws SyncCoreException also when the pull was accepted but the
     *                           answer is not a JSON object
     * @throws \InvalidArgumentException an ID of the item is empty, `.`, `..`
     *                                   or `0`
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
