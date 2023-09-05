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
    // Static features; availability is defined by the library.
    public const FEATURE_REFRESH_AUTHENTICATION = 'site-authentication.refresh';
    public const FEATURE_INDEPENDENT_FLOW_CONFIG = 'flow.independent-config';
    public const FEATURE_PULL_ALL_WITHOUT_POOL = 'migration.no-pool';
    public const FEATURE_PUSH_TO_MULTIPLE_POOLS = 'syndication.multiple-pools';

    // Dynamic features; availability is defined by the Sync Core.
    public const FEATURE_DYNAMIC_POOL_ASSIGNMENT = 'syndication:dynamic-pool-assignment';
    public const FEATURE_PUSH_ASYNC = 'syndication:push-async';
    public const FEATURE_REQUEST_PER_TRANSLATION_AVAILABLE = 'syndication:request-per-translation:available';
    public const FEATURE_REQUEST_PER_TRANSLATION = 'syndication:request-per-translation';
    public const FEATURE_SKIP_UNCHANGED_TRANSLATIONS_AVAILABLE = 'syndication:skip-unchanged-translations:available';
    public const FEATURE_SKIP_UNCHANGED_TRANSLATIONS = 'syndication:skip-unchanged-translations';

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
     * @return null|IEmbedService
     */
    public function getEmbedService();

    /**
     * @return IBatch
     */
    public function batch();

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
     * @param null|string $id
     *                        Optional ID of the site. Defaults to the ID of the
     *                        current site.
     *
     * @return null|string
     */
    public function getSiteName($id = null);

    /**
     * Get the Sync Core internal ID for the site.
     *
     * @param string $uuid
     *
     * @return string
     */
    public function getInternalSiteId($uuid);

    /**
     * Get the Sync Core external ID for the site (a UUID).
     *
     * @param string $id
     *
     * @return string
     */
    public function getExternalSiteId($id);

    /**
     * Set the domains that are used for this site. Customers are invoiced per
     * domain.
     */
    public function setDomains(array $domains);

    /**
     * Update the site name at the Sync Core so that other sites see the new
     * name, e.g. when viewing usages of an entity.
     *
     * @param string $set the new name
     */
    public function setSiteName(string $set);

    /**
     * Update the site details at the Sync Core e.g. to notify the Sync Core of
     * changed REST routes.
     */
    public function updateSiteAtSyncCore();

    /**
     * @throws NotFoundException
     *
     * @return null|array
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
     * @throws SiteVerificationFailedException
     *
     * @return string
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

    /**
     * @return bool
     */
    public function featureEnabled(string $name);

    /**
     * @return float[]
     */
    public function getFeatures();
}
