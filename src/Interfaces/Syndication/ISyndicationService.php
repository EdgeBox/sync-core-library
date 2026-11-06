<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Interfaces\ISyncCore;
use EdgeBox\SyncCore\V2\Raw\Model\MigrationSummary;
use EdgeBox\SyncCore\V2\Raw\Model\PreviewsListResponse;
use EdgeBox\SyncCore\V2\Raw\Model\PullDashboardConfiguration;
use EdgeBox\SyncCore\V2\Raw\Model\SyndicationEntityWithUsage;
use EdgeBox\SyncCore\V2\Raw\Model\TaskGroupEntity;

interface ISyndicationService
{
    /**
     * @return null|IConfigurePullDashboard
     */
    public function configurePullDashboard();

    /**
     * @return IMassPull
     */
    public function massPull();

    /**
     * @return IMassPush
     */
    public function massPush();

    /**
     * @return ITriggerPullSingle
     */
    public function pullSingle(string $flow_id, string $type, string $bundle, string $entity_id);

    /**
     * @return IPullAll
     */
    public function pullAll(string $flow_id, string $type, string $bundle, string $version);

    /**
     * @return IPullOperation
     */
    public function handlePull(string $flow_id, string $type, string $bundle, array $data, bool $delete);

    /**
     * @param string      $entity_uuid
     *                                 The UUID of the entity as shared across all sites
     * @param null|string $entity_id
     *                                 The ID of the entity **if it should be kept**
     *
     * @return IPushSingle
     */
    public function pushSingle(string $flow_id, string $type, string $bundle, string $version_id, string $root_language, string $entity_uuid, ?string $entity_id);

    /**
     * @return IPushMultiple
     */
    public function pushMultiple(string $flow_id);

    /**
     * Inform the Sync Core that the given entity was deleted locally.
     */
    public function deletedLocally(string $flow_id, string $type, string $bundle, string $language, string $entity_uuid, ?string $entity_id);

    /**
     * Get a list of all sites using the given entity from this pool.
     *
     * @return array ['SITE_ID'] => 'DEEP_LINK'
     */
    public function getExternalUsages(string $pool_id, string $type, string $bundle, string $shared_entity_id);

    /**
     * Read the Flows, Pools and entity types the pull dashboard offers this
     * site.
     *
     * @param bool $configuration_access whether to read with the site's
     *                                   configuration scope, as the dashboard
     *                                   does for an administrator, rather than
     *                                   its content scope
     *
     * @return PullDashboardConfiguration
     *
     * @throws SyncCoreException
     */
    public function getPullDashboardConfiguration(bool $configuration_access);

    /**
     * List the content one Flow can pull into this site, one page at a time.
     * Available when the feature {@see ISyncCore::FEATURE_PREVIEWS} is enabled.
     *
     * @param array{
     *     search?: null|string,
     *     entityTypeNamespaceMachineNames?: null|string,
     *     entityTypeMachineNames?: null|string,
     *     poolMachineNames?: null|string,
     *     sourceSiteId?: null|string,
     *     publishedEarliest?: null|string,
     *     publishedLatest?: null|string,
     *     existsLocally?: null|string,
     *     deleted?: null|string,
     *     deletedLocally?: null|string,
     *     page?: null|string,
     *     itemsPerPage?: null|string,
     * } $query the filters, keyed by the Sync Core's query names; a missing,
     *   null or empty value and the value `'0'` leave the filter out, so the
     *   Sync Core applies its default, and a key not named here is not sent
     * @param bool $configuration_access see {@see getPullDashboardConfiguration()}
     *
     * @return PreviewsListResponse one page of the content
     *
     * @throws SyncCoreException
     * @throws \InvalidArgumentException the Flow ID is empty, `.`, `..` or `0`
     */
    public function listPreviews(string $flow_id, array $query, bool $configuration_access);

    /**
     * Read one task, with the usage of its item on this site.
     *
     * @return SyndicationEntityWithUsage
     *
     * @throws SyncCoreException
     * @throws \InvalidArgumentException the task ID is empty, `.`, `..` or `0`
     */
    public function getTask(string $task_id);

    /**
     * Read one task group, such as a pull-all or a manual push.
     *
     * @param bool $configuration_access see {@see getPullDashboardConfiguration()}
     *
     * @return TaskGroupEntity
     *
     * @throws SyncCoreException
     * @throws \InvalidArgumentException the task group ID is empty, `.`, `..` or `0`
     */
    public function getTaskGroup(string $task_group_id, bool $configuration_access);

    /**
     * Count the tasks of one task group per status.
     *
     * @return MigrationSummary
     *
     * @throws SyncCoreException
     * @throws \InvalidArgumentException the task group ID is empty, `.`, `..` or `0`
     */
    public function getTaskGroupSummary(string $task_group_id);

    /**
     * Read the task group of the newest manual push of this site through the
     * given Flow: the first item of the list the Sync Core answers with.
     *
     * @return null|TaskGroupEntity null when the list has no item, as when
     *                              this site pushed nothing manually through
     *                              the Flow
     *
     * @throws SyncCoreException
     * @throws \InvalidArgumentException the Flow ID is empty, `.`, `..` or `0`
     */
    public function getNewestManualPushTaskGroup(string $flow_id);

    /**
     * Trigger the login procedure from the Sync Core to the site. Only relevant
     * for cookie authentication e.g. when you cleared all sessions.
     *
     * @return bool
     *
     * @throws SyncCoreException
     */
    public function refreshAuthentication();
}
