<?php

namespace EdgeBox\SyncCore\Interfaces;

use GuzzleHttp\Client;

/**
 * Interface ApplicationInterface.
 *
 * Every application using the Sync Core library must implement this interface
 * and provide it to the client. It provides all basic information about the
 * application itself to the Sync Core.
 */
interface IApplicationInterface
{
    /**
     * @var string AUTHENTICATION_TYPE_BASIC_AUTH always send username:password
     *             as Authentication header when making REST requests to this site
     */
    public const AUTHENTICATION_TYPE_BASIC_AUTH = 'basic_auth';
    /**
     * @var string AUTHENTICATION_TYPE_COOKIE Use Drupal default cookie
     *             authentication before making any REST requests to this site
     */
    public const AUTHENTICATION_TYPE_COOKIE = 'cookie';
    public const SYNC_CORE_PERMISSIONS_CONFIGURATION = 'configuration';
    public const SYNC_CORE_PERMISSIONS_CONTENT = 'content';

    /**
     * @var string REST_ACTION_CREATE_ENTITY Create OR Update an entity
     */
    public const REST_ACTION_CREATE_ENTITY = 'create';
    /**
     * @var string REST_ACTION_DELETE_ENTITY Delete the given entity
     */
    public const REST_ACTION_DELETE_ENTITY = 'delete';
    /**
     * @var string REST_ACTION_RETRIEVE_ENTITY
     *             Get details about the given entity.
     *             404 means it doesn't exist (any longer).
     */
    public const REST_ACTION_RETRIEVE_ENTITY = 'retrieve';
    /**
     * @var string REST_ACTION_LIST_ENTITIES
     *
     * List all entities of the site. Will include a "mode" query parameter to get one of
     * the lists defined below.
     */
    public const REST_ACTION_LIST_ENTITIES = 'list';
    /**
     * @var string REST_ACTION_SITE_STATUS
     *
     * Provide some status information about the site like the CMS version and
     * module/plugin version
     */
    public const REST_ACTION_SITE_STATUS = 'status';
    /**
     * @var string REST_ACTION_SITE_CONFIG
     *
     * Export relevant configuration from the site to the Sync Core like the
     * entity type definitions, Pools and Flows
     */
    public const REST_ACTION_SITE_CONFIG = 'config';

    /**
     * @var string FLOW_NONE
     *
     * When requesting entities with the site's REST interfaces, this special
     * name can be used to request entities without a Flow existing yet. This is
     * used by the embed service to query for entities, e.g. when creating a
     * Flow to retrieve tags to filter by.
     */
    public const FLOW_NONE = '_';

    /**
     * @return IApplicationInterface
     */
    public static function get();

    /**
     * Provide a unique identifier for the site. The ID is created by the Sync
     * Core and then stored at the site. All following requests provide this
     * unique identifier.
     * Make sure to use SyncCore::verifySiteId() before exporting any
     * configuration. This ensures that the site identifies as the same site
     * that the Sync Core expects. If you're deploying a database dump for
     * example, the site ID will remain but the URL will change and most likely
     * what you actually want to do is register a new site. So the Sync Core will
     * validate that this ID is indeed not registered with another base URL.
     * Abort all operations unless the ID is valid!
     * May return NULL in case the site isn't registered yet.
     *
     * @return null|string
     */
    public function getSiteId();

    /**
     * After registering this site, save the default Sync Core URL to be assigned when creating
     * a pool.
     */
    public function setSyncCoreUrl(string $set);

    /**
     * Get the default Sync Core URL of the site.
     *
     * @return null|string
     */
    public function getSyncCoreUrl();

    /**
     * After registering this site, save the ID of this site locally for later
     * access.
     */
    public function setSiteId(string $set);

    /**
     * Set the globally unique identifier for this site. This identifier will be the same
     * across all Content Sync services and Sync Cores.
     */
    public function setSiteUuid(string $set);

    /**
     * Get the globally unique identifier for this site. This identifier will be the same
     * across all Content Sync services and Sync Cores.
     *
     * @return string
     */
    public function getSiteUuid();

    /**
     * Human readable name for the site.
     *
     * @return string
     */
    public function getSiteName();

    /**
     * A unique identifier for this site, but human readable. Will be removed in
     * a future release and is there for legacy reasons only.
     *
     * @return null|string
     */
    public function getSiteMachineName();

    /**
     * A unique identifier for this site, but human readable. Will be removed in
     * a future release and is there for legacy reasons only.
     */
    public function setSiteMachineName(string $set);

    /**
     * Provide the base URL for the site.
     *
     * @return string
     */
    public function getSiteBaseUrl();

    /**
     * Provide the URLs that the Sync Core can use to create, update and delete
     * entities.
     *
     * Used by the Sync Core v1.
     *
     * @param null|string $entity_uuid
     * @param null|string $manually
     *                                   Will be "true" or "false" (string!)
     * @param null|string $as_dependency
     *                                   Will be "true" or "false" (string!)
     *
     * @return string
     */
    public function getRestUrl(string $pool_id, string $type_machine_name, string $bundle_machine_name, string $version_id, $entity_uuid = null, $manually = null, $as_dependency = null);

    /**
     * Provide the path/query that the site wants to be called for the individual actions.
     * So this must not include the base URL of the site. Actions are one of:
     * - IApplicationInterface::REST_ACTION_CREATE_ENTITY
     * - IApplicationInterface::REST_ACTION_DELETE_ENTITY
     * - IApplicationInterface::REST_ACTION_RETRIEVE_ENTITY
     * - IApplicationInterface::REST_ACTION_LIST_ENTITIES.
     *
     * Used by the Sync Core v2.
     *
     * @return string
     */
    public function getRelativeReferenceForRestCall(string $flow_machine_name, string $action);

    /**
     * Provide the path/query that the site wants to be called for the individual, global actions.
     * So this must not include the base URL of the site. Actions are one of:
     * IApplicationInterface::REST_ACTION_SITE_STATUS.
     *
     * Used by the Sync Core v2.
     *
     * @return string
     */
    public function getRelativeReferenceForSiteRestCall(string $action);

    /**
     * @return string
     */
    public function getEmbedBaseUrl(string $feature);

    /**
     * @return string[] [
     *                  'type' => {@see IApplicationInterface::AUTHENTICATION_TYPE_BASIC_AUTH} |
     *                  {@see IApplicationInterface::AUTHENTICATION_TYPE_COOKIE},
     *                  'username' => string,
     *                  'password' => string,
     *                  ]
     */
    public function getAuthentication();

    /**
     * Provide a unique identifier for the application. The following IDs are
     * supported right now:
     * - drupal.
     *
     * @return string
     */
    public function getApplicationId();

    /**
     * Get a version (major, optionally minor) of the application as a string.
     * For Drupal this is "8.x" right now.
     *
     * @return string
     */
    public function getApplicationVersion();

    /**
     * Get a version (major + minor) of the application module as a string.
     * For Drupal this is the published module version, e.g. "1.32".
     * If this is not versioned, return 'dev'.
     *
     * @return string
     */
    public function getApplicationModuleVersion();

    /**
     * Provide an HTTP client to use for all requests against the Sync Core. This
     * allows the application to apply any custom settings for the client name,
     * timeouts, other security measures to be applied.
     *
     * @return Client
     */
    public function getHttpClient();

    /**
     * Provide any additional options to the request, e.g. a timeout or proxy
     * settings.
     *
     * @return array[]
     */
    public function getHttpOptions();

    /**
     * Provide what feature flags are configured. expects an array of string => number,
     * e.g. 'previews' => 1,
     * would indicate that previews are enabled.
     *
     * @return array
     */
    public function getFeatureFlags();
}
