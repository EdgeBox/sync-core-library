<?php

declare(strict_types=1);

namespace EdgeBox\SyncCore\Tests\Support;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use GuzzleHttp\Client;

/**
 * A configurable in-memory application for the fixture tests: it holds a mock
 * HTTP client, a site secret and uuid, and the relative references the site
 * reports for each REST action.
 */
final class TestApplication implements IApplicationInterface
{
    /**
     * @var Client
     */
    public $httpClient;

    /**
     * @var string
     */
    public $siteUuid = 'aaaaaaaa-bbbb-4ccc-8ddd-eeeeeeeeeeee';

    /**
     * @var string
     */
    public $secret = 'test-secret';

    /**
     * @var string
     */
    public $siteBaseUrl = 'https://site.example.com';

    /**
     * @var array<string, int>
     */
    public $featureFlags = [];

    /**
     * @var array<string, null|string>
     */
    public $siteRestReferences = [];

    /**
     * @var array<string, string>
     */
    public $restReferences = [];

    public function __construct(?Client $http_client = null)
    {
        $this->httpClient = $http_client ?? new Client();
    }

    public static function get()
    {
        return new self();
    }

    public function getSiteId()
    {
        return $this->siteUuid;
    }

    public function setSyncCoreUrl(string $set) {}

    public function getSyncCoreUrl()
    {
        return null;
    }

    public function setSiteId(string $set) {}

    public function setSiteUuid(string $set)
    {
        $this->siteUuid = $set;
    }

    public function getSiteUuid()
    {
        return $this->siteUuid;
    }

    public function getSiteName()
    {
        return 'Test site';
    }

    public function getSiteMachineName()
    {
        return 'test_site';
    }

    public function setSiteMachineName(string $set) {}

    public function getSiteBaseUrl()
    {
        return $this->siteBaseUrl;
    }

    public function getRestUrl(string $pool_id, string $type_machine_name, string $bundle_machine_name, string $version_id, $entity_uuid = null, $manually = null, $as_dependency = null)
    {
        return $this->siteBaseUrl.'/rest';
    }

    public function getRelativeReferenceForRestCall(string $flow_machine_name, string $action)
    {
        return $this->restReferences[$action] ?? '/rest/'.$action;
    }

    public function getRelativeReferenceForSiteRestCall(string $action)
    {
        return $this->siteRestReferences[$action] ?? null;
    }

    public function getEmbedBaseUrl(string $feature)
    {
        return $this->siteBaseUrl.'/embed/'.$feature;
    }

    public function getAuthentication()
    {
        return [
            'type' => IApplicationInterface::AUTHENTICATION_TYPE_BASIC_AUTH,
            'username' => 'test',
            'password' => $this->secret,
        ];
    }

    public function getApplicationId()
    {
        return 'drupal';
    }

    public function getApplicationVersion()
    {
        return '10.x';
    }

    public function getApplicationModuleVersion()
    {
        return '2.0';
    }

    public function getHttpClient()
    {
        return $this->httpClient;
    }

    public function getHttpOptions()
    {
        return [];
    }

    public function getFeatureFlags()
    {
        return $this->featureFlags;
    }
}
