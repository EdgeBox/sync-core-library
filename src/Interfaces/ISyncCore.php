<?php

namespace EdgeBox\SyncCore\Interfaces;

use EdgeBox\SyncCore\Exception\NotFoundException;
use EdgeBox\SyncCore\Exception\SiteVerificationFailedException;
use EdgeBox\SyncCore\Interfaces\Configuration\IConfigurationService;
use EdgeBox\SyncCore\Interfaces\Embed\IEmbedService;
use EdgeBox\SyncCore\Interfaces\Syndication\ISyndicationService;

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
     * @return ISyndicationService
     */
    public function getSyndicationService();

    /**
     * @return IConfigurationService
     */
    public function getConfigurationService();

    /**
     * @return IEmbedService|null
     */
    public function getEmbedService();

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
     * @param bool|null $set
     *
     * @return bool|null
     */
    public function isDirectUserAccessEnabled($set = null);

    /**
     * @param string|null $id
     *                        Optional ID of the site. Defaults to the ID of the
     *                        current site.
     *
     * @return string|null
     */
    public function getSiteName($id = null);

    public function setSiteName(string $set);

    /**
     * @return array|null
     *
     * @throws NotFoundException
     */
    public function verifySiteId();

    /**
     * @return bool
     */
    public function isSiteRegistered();

    /**
     * @param bool $force
     *                    Force updating any existing configuration (skip
     *                    verification from above)
     *
     * @return string
     *
     * @throws SiteVerificationFailedException
     */
    public function registerSite($force = false);

    /**
     * Get a list of all sites from this pool that use a different version ID and
     * provide a diff on field basis.
     *
     * Return format is:
     * ['SITE_ID']['remote_missing' | 'local_missing'][] = 'PROPERTY_NAME'
     *
     * @return array
     */
    public function getSitesWithDifferentEntityTypeVersion(string $pool_id, string $entity_type, string $bundle, string $target_version);

    /**
     * @return string[]
     */
    public function getReservedPropertyNames();
}
