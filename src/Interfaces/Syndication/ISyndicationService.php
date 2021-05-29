<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

use EdgeBox\SyncCore\Exception\SyncCoreException;

interface ISyndicationService
{
    /**
     * @return IConfigurePullDashboard|null
     */
    public function configurePullDashboard();

    /**
     * @return ITriggerPullSingle
     */
    public function pullSingle(string $flow_id, string $type, string $bundle, string $entity_id, bool $delete);

    /**
     * @return IPullAll
     */
    public function pullAll(string $flow_id, string $type, string $bundle);

    /**
     * @return IPullOperation
     */
    public function handlePull(string $flow_id, string $type, string $bundle, array $data);

    /**
     * @param string      $entity_uuid
     *                                 The UUID of the entity as shared across all sites
     * @param string|null $entity_id
     *                                 The ID of the entity **if it should be kept**
     *
     * @return IPushSingle
     */
    public function pushSingle(string $flow_id, string $type, string $bundle, string $version_id, string $root_language, string $entity_uuid, ?string $entity_id);

    /**
     * Get a list of all sites using the given entity from this pool.
     *
     * @return array ['SITE_ID'] => 'DEEP_LINK'
     */
    public function getExternalUsages(string $pool_id, string $type, string $bundle, string $shared_entity_id);

    /**
     * Trigger the login procedure from the Sync Core to the site. Only relevant
     * for cookie authentication e.g. when you cleared all sessions.
     *
     * @return bool
     *
     * @throws SyncCoreException
     */
    public function refreshAuthentication();

    /**
     * Whether or not the `$this->refreshAuthentication()` method is supported.
     *
     * @return bool
     */
    public function canRefreshAuthentication();
}
