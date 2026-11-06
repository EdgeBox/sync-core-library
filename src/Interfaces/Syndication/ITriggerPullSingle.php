<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Exception\TimeoutException;
use EdgeBox\SyncCore\V2\Raw\Model\TaskEntity;

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
     * Name the item by its unique ID, the ID a site shares in place of a UUID,
     * whatever its shape.
     *
     * The ID the pull was created with is sorted into a UUID or a unique ID by
     * its shape, which a UUID in upper case or of another version than 4
     * doesn't have. Once an ID is named by role, the ID the pull was created
     * with takes the other role, unless it is the ID named here.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException the unique ID is empty, `.`, `..` or `0`
     */
    public function withUniqueId(string $unique_id);

    /**
     * Name the item by its UUID, whatever its shape. As for
     * {@see withUniqueId()}, the ID the pull was created with takes the other
     * role, unless it is the ID named here.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException the UUID is empty, `.`, `..` or `0`
     */
    public function withUuid(string $uuid);

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
     * @return TaskEntity the task the Sync Core created
     *
     * @throws TimeoutException  the Sync Core did not answer
     * @throws SyncCoreException after the pull was sent, such as for a
     *                           gateway timeout or a connection that broke
     *                           during the answer; the pull may have reached
     *                           the Sync Core whichever exception is thrown,
     *                           so a caller never sends it again in its place
     * @throws \InvalidArgumentException an ID of the item, the Flow or a Pool
     *                                   is empty, `.`, `..` or `0`
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
