<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

use EdgeBox\SyncCore\Exception\SyncCoreException;

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
     * Every answer of the reading methods below is the JSON object the Sync
     * Core sent, as an associative array: a field the Sync Core leaves out is
     * missing from the array, and a status this library does not know is
     * returned as it was sent.
     *
     * @param bool $configuration_access whether to read with the site's
     *                                   configuration scope, as the dashboard
     *                                   does for an administrator, rather than
     *                                   its content scope
     *
     * @return array<string, mixed>
     *
     * @throws SyncCoreException
     */
    public function getPullDashboardConfiguration(bool $configuration_access);

    /**
     * List the content one Flow can pull into this site, one page at a time.
     *
     * @param array<string, null|string> $query the filters, keyed by the Sync
     *                                          Core's query names:
     *                                          `search`,
     *                                          `entityTypeNamespaceMachineNames`,
     *                                          `entityTypeMachineNames`,
     *                                          `poolMachineNames`,
     *                                          `sourceSiteId`,
     *                                          `publishedEarliest`,
     *                                          `publishedLatest`,
     *                                          `existsLocally`, `deleted`,
     *                                          `deletedLocally`, `page` and
     *                                          `itemsPerPage`; a missing or
     *                                          null value leaves the filter out
     * @param bool $configuration_access see {@see getPullDashboardConfiguration()}
     *
     * @return array<string, mixed> the page: `items`, `page`,
     *                              `numberOfPages`, `itemsPerPage` and
     *                              `totalNumberOfItems`
     *
     * @throws SyncCoreException
     */
    public function listPreviews(string $flow_id, array $query, bool $configuration_access);

    /**
     * Read one task, with the usage of its item on this site.
     *
     * @return array<string, mixed>
     *
     * @throws SyncCoreException
     */
    public function getTask(string $task_id);

    /**
     * Read one task group, such as a pull-all or a manual push.
     *
     * @param bool $configuration_access see {@see getPullDashboardConfiguration()}
     *
     * @return array<string, mixed>
     *
     * @throws SyncCoreException
     */
    public function getTaskGroup(string $task_group_id, bool $configuration_access);

    /**
     * Count the tasks of one task group per status.
     *
     * @return array<string, mixed>
     *
     * @throws SyncCoreException
     */
    public function getTaskGroupSummary(string $task_group_id);

    /**
     * Read the task group of the newest manual push of this site through the
     * given Flow.
     *
     * @return null|array<string, mixed> null when this site pushed nothing
     *                                   manually through the Flow
     *
     * @throws SyncCoreException
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
