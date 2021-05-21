<?php

namespace EdgeBox\SyncCore\Interfaces;

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
     * After registering this site, save the ID of this site locally for later
     * access.
     *
     * @param string $set
     */
    public function setSiteId($set);

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
     * @param string $set
     */
    public function setSiteMachineName($set);

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
     * @param string      $pool_id
     * @param string      $type_machine_name
     * @param string      $bundle_machine_name
     * @param string      $version_id
     * @param string|null $entity_uuid
     * @param string|null $manually
     *                                         Will be "true" or "false" (string!)
     * @param string|null $as_dependency
     *                                         Will be "true" or "false" (string!)
     *
     * @return string
     */
    public function getRestUrl($pool_id, $type_machine_name, $bundle_machine_name, $version_id, $entity_uuid = null, $manually = null, $as_dependency = null);

    /**
     * @return array [
     *               'type' => {@see IApplicationInterface::AUTHENTICATION_TYPE_BASIC_AUTH} |
     *               {@see IApplicationInterface::AUTHENTICATION_TYPE_COOKIE},
     *               'username' => string,
     *               'password' => string,
     *               ]
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
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient();

    /**
     * Provide any additional options to the request, e.g. a timeout or proxy
     * settings.
     *
     * @return array
     */
    public function getHttpOptions();
}
