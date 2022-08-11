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
     * Trigger the login procedure from the Sync Core to the site. Only relevant
     * for cookie authentication e.g. when you cleared all sessions.
     *
     * @throws SyncCoreException
     *
     * @return bool
     */
    public function refreshAuthentication();
}
