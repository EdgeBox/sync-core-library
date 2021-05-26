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
     * @return string|null
     */
    public function getSiteId();

    /**
     * After registering this site, save the default Sync Core URL to be assigned when creating
     * a pool.
     *
     * @return void
     */
    public function setSyncCoreUrl(string $set);

    /**
     * Get the default Sync Core URL of the site.
     *
     * @return string|null
     */
    public function getSyncCoreUrl();

    /**
     * After registering this site, save the ID of this site locally for later
     * access.
     *
     * @return void
     */
    public function setSiteId(string $set);

    /**
     * Set the globally unique identifier for this site. This identifier will be the same
     * across all Content Sync services and Sync Cores.
     *
     * @return void
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
     * @return string|null
     */
    public function getSiteMachineName();

    /**
     * A unique identifier for this site, but human readable. Will be removed in
     * a future release and is there for legacy reasons only.
     *
     * @return void
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
     * @param string|null $entity_uuid
     * @param string|null $manually
     *                                   Will be "true" or "false" (string!)
     * @param string|null $as_dependency
     *                                   Will be "true" or "false" (string!)
     *
     * @return string
     */
    public function getRestUrl(string $pool_id, string $type_machine_name, string $bundle_machine_name, string $version_id, $entity_uuid = null, $manually = null, $as_dependency = null);

    /**
     * Provide the path/query that the site wants to be called for the individual actions.
     * So this must not include the base URL of the site. Actions are one of:
     * IApplicationInterface::REST_ACTION_*.
     *
     * Used by the Sync Core v2.
     *
     * @return string
     */
    public function getRelativeReferenceForRestCall(string $flow_machine_name, string $action, string $shared_entity_id = null);

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
}
