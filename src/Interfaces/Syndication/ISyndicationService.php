<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

interface ISyndicationService
{
    /**
     * @return IConfigurePullDashboard|null
     */
    public function configurePullDashboard();

    /**
     * @param string $flow_id
     * @param string $type
     * @param string $bundle
     * @param string $entity_id
     *
     * @return ITriggerPullSingle
     */
    public function pullSingle($flow_id, $type, $bundle, $entity_id);

    /**
     * @param string $flow_id
     * @param string $type
     * @param string $bundle
     *
     * @return IPullAll
     */
    public function pullAll($flow_id, $type, $bundle);

    /**
     * @param string $flow_id
     * @param string $type
     * @param string $bundle
     * @param array  $data
     *
     * @return IPullOperation
     */
    public function handlePull($flow_id, $type, $bundle, $data);

    /**
     * @param string      $flow_id
     * @param string      $type
     * @param string      $bundle
     * @param string      $entity_uuid
     *                                 The UUID of the entity as shared across all sites
     * @param string|null $entity_id
     *                                 The ID of the entity **if it should be kept**
     *
     * @return IPushSingle
     */
    public function pushSingle($flow_id, $type, $bundle, $entity_uuid, $entity_id);

    /**
     * Get a list of all sites using the given entity from this pool.
     *
     * @param string $pool_id
     * @param string $type
     * @param string $bundle
     * @param string $shared_entity_id
     *
     * @return array ['SITE_ID'] => 'DEEP_LINK'
     */
    public function getExternalUsages($pool_id, $type, $bundle, $shared_entity_id);

    /**
     * Trigger the login procedure from the Sync Core to the site. Only relevant
     * for cookie authentication e.g. when you cleared all sessions.
     *
     * @return bool
     *
     * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
     */
    public function refreshAuthentication();
}
