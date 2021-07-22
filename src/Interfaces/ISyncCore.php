<?php

namespace EdgeBox\SyncCore\Interfaces;

/**
 * Interface ISyncCore.
 *
 * The interface both the 1.x Sync Core and the 2.x Sync Core must provide.
 */
interface ISyncCore
{
    /**
     * @return IReportingService
     */
    public function getReportingService();

    /**
     * @return \EdgeBox\SyncCore\Interfaces\Syndication\ISyndicationService
     */
    public function getSyndicationService();

    /**
     * @return \EdgeBox\SyncCore\Interfaces\Configuration\IConfigurationService
     */
    public function getConfigurationService();

    /**
     * @return IBatch
     */
    public function batch();

    /**
     * @return bool
     */
    public function canHandleFlowConfigurationIndependently();

    /**
     * Whether the Sync Core allows to be called by clients directly for the pull
     * dashboard.
     *
     * @param null|bool $set
     *
     * @return null|bool
     */
    public function isDirectUserAccessEnabled($set = null);

    /**
     * @param string $id
     *                   Optional ID of the site. Defaults to the ID of the
     *                   current site.
     *
     * @return string
     */
    public function getSiteName($id = null);

    /**
     * @param bool $set
     */
    public function setSiteName($set);

    /**
     * @throws \EdgeBox\SyncCore\Exception\NotFoundException
     *
     * @return null|array
     */
    public function verifySiteId();

    /**
     * @param bool $force
     *                    Force updating any existing configuration (skip
     *                    verification from above)
     *
     * @throws \EdgeBox\SyncCore\Exception\SiteVerificationFailedException
     *
     * @return string
     */
    public function registerSite($force = false);

    /**
     * Get a list of all sites from this pool that use a different version ID and
     * provide a diff on field basis.
     *
     * @param string $pool_id
     * @param string $entity_type
     * @param string $bundle
     * @param string $target_version
     *
     * @return array['SITE_ID']['remote_missing' | 'local_missing'][] =
     *                                           'PROPERTY_NAME'
     */
    public function getSitesWithDifferentEntityTypeVersion($pool_id, $entity_type, $bundle, $target_version);

    /**
     * @return string[]
     */
    public function getReservedPropertyNames();
}
