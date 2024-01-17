<?php
/**
 * SiteSelfDtoSite.
 *
 * PHP version 7.4
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 */

/**
 * Sync Core.
 *
 * The Sync Core that sends and receives content from all connected sites and services for Content Sync.
 *
 * The version of the OpenAPI document: 1.0
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 6.4.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace EdgeBox\SyncCore\V2\Raw\Model;

use ArrayAccess;
use EdgeBox\SyncCore\V2\Raw\ObjectSerializer;

/**
 * SiteSelfDtoSite Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class SiteSelfDtoSite implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'SiteSelfDto_site';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'name' => 'string',
        'deprecatedMachineName' => 'string',
        'baseUrl' => 'string',
        'status' => '\EdgeBox\SyncCore\V2\Raw\Model\SiteStatus',
        'inactiveSince' => 'float',
        'appType' => '\EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType',
        'appVersion' => 'string',
        'appModuleVersion' => 'string',
        'useProxy' => 'bool',
        'domains' => 'string[]',
        'extensions' => '\EdgeBox\SyncCore\V2\Raw\Model\SiteExtension[]',
        'featureFlags' => 'mixed',
        'uuid' => 'string',
        'environmentType' => '\EdgeBox\SyncCore\V2\Raw\Model\SiteEnvironmentType',
        'customer' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity',
        'contract' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity',
        'project' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity',
        'lastActivity' => 'float',
        'id' => 'string',
        'createdAt' => 'float',
        'updatedAt' => 'float',
        'deletedAt' => 'float',
        'secret' => 'string',
        'restUrls' => '\EdgeBox\SyncCore\V2\Raw\Model\RegisterNewSiteDtoRestUrls',
        'maxRequestsPerMinute' => 'float',
        'maxParallelRequests' => 'float',
        'notAvailableCounter' => 'float',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static $openAPIFormats = [
        'name' => null,
        'deprecatedMachineName' => null,
        'baseUrl' => null,
        'status' => null,
        'inactiveSince' => null,
        'appType' => null,
        'appVersion' => null,
        'appModuleVersion' => null,
        'useProxy' => null,
        'domains' => null,
        'extensions' => null,
        'featureFlags' => null,
        'uuid' => null,
        'environmentType' => null,
        'customer' => null,
        'contract' => null,
        'project' => null,
        'lastActivity' => null,
        'id' => null,
        'createdAt' => null,
        'updatedAt' => null,
        'deletedAt' => null,
        'secret' => null,
        'restUrls' => null,
        'maxRequestsPerMinute' => null,
        'maxParallelRequests' => null,
        'notAvailableCounter' => null,
    ];

    /**
     * Array of nullable properties. Used for (de)serialization.
     *
     * @var bool[]
     */
    protected static array $openAPINullables = [
        'name' => false,
        'deprecatedMachineName' => true,
        'baseUrl' => false,
        'status' => false,
        'inactiveSince' => true,
        'appType' => false,
        'appVersion' => false,
        'appModuleVersion' => false,
        'useProxy' => true,
        'domains' => true,
        'extensions' => true,
        'featureFlags' => true,
        'uuid' => false,
        'environmentType' => false,
        'customer' => false,
        'contract' => false,
        'project' => false,
        'lastActivity' => false,
        'id' => false,
        'createdAt' => false,
        'updatedAt' => false,
        'deletedAt' => true,
        'secret' => true,
        'restUrls' => false,
        'maxRequestsPerMinute' => true,
        'maxParallelRequests' => true,
        'notAvailableCounter' => true,
    ];

    /**
     * If a nullable field gets set to null, insert it here.
     *
     * @var bool[]
     */
    protected array $openAPINullablesSetToNull = [];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'name' => 'name',
        'deprecatedMachineName' => 'deprecatedMachineName',
        'baseUrl' => 'baseUrl',
        'status' => 'status',
        'inactiveSince' => 'inactiveSince',
        'appType' => 'appType',
        'appVersion' => 'appVersion',
        'appModuleVersion' => 'appModuleVersion',
        'useProxy' => 'useProxy',
        'domains' => 'domains',
        'extensions' => 'extensions',
        'featureFlags' => 'featureFlags',
        'uuid' => 'uuid',
        'environmentType' => 'environmentType',
        'customer' => 'customer',
        'contract' => 'contract',
        'project' => 'project',
        'lastActivity' => 'lastActivity',
        'id' => 'id',
        'createdAt' => 'createdAt',
        'updatedAt' => 'updatedAt',
        'deletedAt' => 'deletedAt',
        'secret' => 'secret',
        'restUrls' => 'restUrls',
        'maxRequestsPerMinute' => 'maxRequestsPerMinute',
        'maxParallelRequests' => 'maxParallelRequests',
        'notAvailableCounter' => 'notAvailableCounter',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'name' => 'setName',
        'deprecatedMachineName' => 'setDeprecatedMachineName',
        'baseUrl' => 'setBaseUrl',
        'status' => 'setStatus',
        'inactiveSince' => 'setInactiveSince',
        'appType' => 'setAppType',
        'appVersion' => 'setAppVersion',
        'appModuleVersion' => 'setAppModuleVersion',
        'useProxy' => 'setUseProxy',
        'domains' => 'setDomains',
        'extensions' => 'setExtensions',
        'featureFlags' => 'setFeatureFlags',
        'uuid' => 'setUuid',
        'environmentType' => 'setEnvironmentType',
        'customer' => 'setCustomer',
        'contract' => 'setContract',
        'project' => 'setProject',
        'lastActivity' => 'setLastActivity',
        'id' => 'setId',
        'createdAt' => 'setCreatedAt',
        'updatedAt' => 'setUpdatedAt',
        'deletedAt' => 'setDeletedAt',
        'secret' => 'setSecret',
        'restUrls' => 'setRestUrls',
        'maxRequestsPerMinute' => 'setMaxRequestsPerMinute',
        'maxParallelRequests' => 'setMaxParallelRequests',
        'notAvailableCounter' => 'setNotAvailableCounter',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'name' => 'getName',
        'deprecatedMachineName' => 'getDeprecatedMachineName',
        'baseUrl' => 'getBaseUrl',
        'status' => 'getStatus',
        'inactiveSince' => 'getInactiveSince',
        'appType' => 'getAppType',
        'appVersion' => 'getAppVersion',
        'appModuleVersion' => 'getAppModuleVersion',
        'useProxy' => 'getUseProxy',
        'domains' => 'getDomains',
        'extensions' => 'getExtensions',
        'featureFlags' => 'getFeatureFlags',
        'uuid' => 'getUuid',
        'environmentType' => 'getEnvironmentType',
        'customer' => 'getCustomer',
        'contract' => 'getContract',
        'project' => 'getProject',
        'lastActivity' => 'getLastActivity',
        'id' => 'getId',
        'createdAt' => 'getCreatedAt',
        'updatedAt' => 'getUpdatedAt',
        'deletedAt' => 'getDeletedAt',
        'secret' => 'getSecret',
        'restUrls' => 'getRestUrls',
        'maxRequestsPerMinute' => 'getMaxRequestsPerMinute',
        'maxParallelRequests' => 'getMaxParallelRequests',
        'notAvailableCounter' => 'getNotAvailableCounter',
    ];

    /**
     * Associative array for storing property values.
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor.
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->setIfExists('name', $data ?? [], null);
        $this->setIfExists('deprecatedMachineName', $data ?? [], null);
        $this->setIfExists('baseUrl', $data ?? [], null);
        $this->setIfExists('status', $data ?? [], null);
        $this->setIfExists('inactiveSince', $data ?? [], null);
        $this->setIfExists('appType', $data ?? [], null);
        $this->setIfExists('appVersion', $data ?? [], null);
        $this->setIfExists('appModuleVersion', $data ?? [], null);
        $this->setIfExists('useProxy', $data ?? [], null);
        $this->setIfExists('domains', $data ?? [], null);
        $this->setIfExists('extensions', $data ?? [], null);
        $this->setIfExists('featureFlags', $data ?? [], null);
        $this->setIfExists('uuid', $data ?? [], null);
        $this->setIfExists('environmentType', $data ?? [], null);
        $this->setIfExists('customer', $data ?? [], null);
        $this->setIfExists('contract', $data ?? [], null);
        $this->setIfExists('project', $data ?? [], null);
        $this->setIfExists('lastActivity', $data ?? [], null);
        $this->setIfExists('id', $data ?? [], null);
        $this->setIfExists('createdAt', $data ?? [], null);
        $this->setIfExists('updatedAt', $data ?? [], null);
        $this->setIfExists('deletedAt', $data ?? [], null);
        $this->setIfExists('secret', $data ?? [], null);
        $this->setIfExists('restUrls', $data ?? [], null);
        $this->setIfExists('maxRequestsPerMinute', $data ?? [], null);
        $this->setIfExists('maxParallelRequests', $data ?? [], null);
        $this->setIfExists('notAvailableCounter', $data ?? [], null);
    }

    /**
     * Gets the string presentation of the object.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Checks if a property is nullable.
     */
    public static function isNullable(string $property): bool
    {
        return self::openAPINullables()[$property] ?? false;
    }

    /**
     * Checks if a nullable property is set to null.
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if (null === $this->container['name']) {
            $invalidProperties[] = "'name' can't be null";
        }
        if (null === $this->container['baseUrl']) {
            $invalidProperties[] = "'baseUrl' can't be null";
        }
        if (null === $this->container['status']) {
            $invalidProperties[] = "'status' can't be null";
        }
        if (null === $this->container['appType']) {
            $invalidProperties[] = "'appType' can't be null";
        }
        if (null === $this->container['appVersion']) {
            $invalidProperties[] = "'appVersion' can't be null";
        }
        if (null === $this->container['appModuleVersion']) {
            $invalidProperties[] = "'appModuleVersion' can't be null";
        }
        if (null === $this->container['uuid']) {
            $invalidProperties[] = "'uuid' can't be null";
        }
        if (null === $this->container['environmentType']) {
            $invalidProperties[] = "'environmentType' can't be null";
        }
        if (null === $this->container['customer']) {
            $invalidProperties[] = "'customer' can't be null";
        }
        if (null === $this->container['contract']) {
            $invalidProperties[] = "'contract' can't be null";
        }
        if (null === $this->container['project']) {
            $invalidProperties[] = "'project' can't be null";
        }
        if (null === $this->container['lastActivity']) {
            $invalidProperties[] = "'lastActivity' can't be null";
        }
        if (null === $this->container['id']) {
            $invalidProperties[] = "'id' can't be null";
        }
        if (null === $this->container['createdAt']) {
            $invalidProperties[] = "'createdAt' can't be null";
        }
        if (null === $this->container['updatedAt']) {
            $invalidProperties[] = "'updatedAt' can't be null";
        }
        if (null === $this->container['restUrls']) {
            $invalidProperties[] = "'restUrls' can't be null";
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed.
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return 0 === count($this->listInvalidProperties());
    }

    /**
     * Gets name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name.
     *
     * @param string $name name
     *
     * @return self
     */
    public function setName($name)
    {
        if (is_null($name)) {
            throw new \InvalidArgumentException('non-nullable name cannot be null');
        }
        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets deprecatedMachineName.
     *
     * @return null|string
     */
    public function getDeprecatedMachineName()
    {
        return $this->container['deprecatedMachineName'];
    }

    /**
     * Sets deprecatedMachineName.
     *
     * @param null|string $deprecatedMachineName deprecatedMachineName
     *
     * @return self
     */
    public function setDeprecatedMachineName($deprecatedMachineName)
    {
        if (is_null($deprecatedMachineName)) {
            array_push($this->openAPINullablesSetToNull, 'deprecatedMachineName');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('deprecatedMachineName', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['deprecatedMachineName'] = $deprecatedMachineName;

        return $this;
    }

    /**
     * Gets baseUrl.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->container['baseUrl'];
    }

    /**
     * Sets baseUrl.
     *
     * @param string $baseUrl baseUrl
     *
     * @return self
     */
    public function setBaseUrl($baseUrl)
    {
        if (is_null($baseUrl)) {
            throw new \InvalidArgumentException('non-nullable baseUrl cannot be null');
        }
        $this->container['baseUrl'] = $baseUrl;

        return $this;
    }

    /**
     * Gets status.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SiteStatus
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SiteStatus $status status
     *
     * @return self
     */
    public function setStatus($status)
    {
        if (is_null($status)) {
            throw new \InvalidArgumentException('non-nullable status cannot be null');
        }
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets inactiveSince.
     *
     * @return null|float
     */
    public function getInactiveSince()
    {
        return $this->container['inactiveSince'];
    }

    /**
     * Sets inactiveSince.
     *
     * @param null|float $inactiveSince inactiveSince
     *
     * @return self
     */
    public function setInactiveSince($inactiveSince)
    {
        if (is_null($inactiveSince)) {
            array_push($this->openAPINullablesSetToNull, 'inactiveSince');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('inactiveSince', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['inactiveSince'] = $inactiveSince;

        return $this;
    }

    /**
     * Gets appType.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType
     */
    public function getAppType()
    {
        return $this->container['appType'];
    }

    /**
     * Sets appType.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType $appType appType
     *
     * @return self
     */
    public function setAppType($appType)
    {
        if (is_null($appType)) {
            throw new \InvalidArgumentException('non-nullable appType cannot be null');
        }
        $this->container['appType'] = $appType;

        return $this;
    }

    /**
     * Gets appVersion.
     *
     * @return string
     */
    public function getAppVersion()
    {
        return $this->container['appVersion'];
    }

    /**
     * Sets appVersion.
     *
     * @param string $appVersion appVersion
     *
     * @return self
     */
    public function setAppVersion($appVersion)
    {
        if (is_null($appVersion)) {
            throw new \InvalidArgumentException('non-nullable appVersion cannot be null');
        }
        $this->container['appVersion'] = $appVersion;

        return $this;
    }

    /**
     * Gets appModuleVersion.
     *
     * @return string
     */
    public function getAppModuleVersion()
    {
        return $this->container['appModuleVersion'];
    }

    /**
     * Sets appModuleVersion.
     *
     * @param string $appModuleVersion appModuleVersion
     *
     * @return self
     */
    public function setAppModuleVersion($appModuleVersion)
    {
        if (is_null($appModuleVersion)) {
            throw new \InvalidArgumentException('non-nullable appModuleVersion cannot be null');
        }
        $this->container['appModuleVersion'] = $appModuleVersion;

        return $this;
    }

    /**
     * Gets useProxy.
     *
     * @return null|bool
     */
    public function getUseProxy()
    {
        return $this->container['useProxy'];
    }

    /**
     * Sets useProxy.
     *
     * @param null|bool $useProxy useProxy
     *
     * @return self
     */
    public function setUseProxy($useProxy)
    {
        if (is_null($useProxy)) {
            array_push($this->openAPINullablesSetToNull, 'useProxy');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('useProxy', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['useProxy'] = $useProxy;

        return $this;
    }

    /**
     * Gets domains.
     *
     * @return null|string[]
     */
    public function getDomains()
    {
        return $this->container['domains'];
    }

    /**
     * Sets domains.
     *
     * @param null|string[] $domains domains
     *
     * @return self
     */
    public function setDomains($domains)
    {
        if (is_null($domains)) {
            array_push($this->openAPINullablesSetToNull, 'domains');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('domains', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['domains'] = $domains;

        return $this;
    }

    /**
     * Gets extensions.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\SiteExtension[]
     */
    public function getExtensions()
    {
        return $this->container['extensions'];
    }

    /**
     * Sets extensions.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\SiteExtension[] $extensions extensions
     *
     * @return self
     */
    public function setExtensions($extensions)
    {
        if (is_null($extensions)) {
            array_push($this->openAPINullablesSetToNull, 'extensions');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('extensions', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['extensions'] = $extensions;

        return $this;
    }

    /**
     * Gets featureFlags.
     *
     * @return null|mixed
     */
    public function getFeatureFlags()
    {
        return $this->container['featureFlags'];
    }

    /**
     * Sets featureFlags.
     *
     * @param null|mixed $featureFlags featureFlags
     *
     * @return self
     */
    public function setFeatureFlags($featureFlags)
    {
        if (is_null($featureFlags)) {
            array_push($this->openAPINullablesSetToNull, 'featureFlags');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('featureFlags', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['featureFlags'] = $featureFlags;

        return $this;
    }

    /**
     * Gets uuid.
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->container['uuid'];
    }

    /**
     * Sets uuid.
     *
     * @param string $uuid uuid
     *
     * @return self
     */
    public function setUuid($uuid)
    {
        if (is_null($uuid)) {
            throw new \InvalidArgumentException('non-nullable uuid cannot be null');
        }
        $this->container['uuid'] = $uuid;

        return $this;
    }

    /**
     * Gets environmentType.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SiteEnvironmentType
     */
    public function getEnvironmentType()
    {
        return $this->container['environmentType'];
    }

    /**
     * Sets environmentType.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SiteEnvironmentType $environmentType environmentType
     *
     * @return self
     */
    public function setEnvironmentType($environmentType)
    {
        if (is_null($environmentType)) {
            throw new \InvalidArgumentException('non-nullable environmentType cannot be null');
        }
        $this->container['environmentType'] = $environmentType;

        return $this;
    }

    /**
     * Gets customer.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity
     */
    public function getCustomer()
    {
        return $this->container['customer'];
    }

    /**
     * Sets customer.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity $customer customer
     *
     * @return self
     */
    public function setCustomer($customer)
    {
        if (is_null($customer)) {
            throw new \InvalidArgumentException('non-nullable customer cannot be null');
        }
        $this->container['customer'] = $customer;

        return $this;
    }

    /**
     * Gets contract.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity
     */
    public function getContract()
    {
        return $this->container['contract'];
    }

    /**
     * Sets contract.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity $contract contract
     *
     * @return self
     */
    public function setContract($contract)
    {
        if (is_null($contract)) {
            throw new \InvalidArgumentException('non-nullable contract cannot be null');
        }
        $this->container['contract'] = $contract;

        return $this;
    }

    /**
     * Gets project.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity
     */
    public function getProject()
    {
        return $this->container['project'];
    }

    /**
     * Sets project.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity $project project
     *
     * @return self
     */
    public function setProject($project)
    {
        if (is_null($project)) {
            throw new \InvalidArgumentException('non-nullable project cannot be null');
        }
        $this->container['project'] = $project;

        return $this;
    }

    /**
     * Gets lastActivity.
     *
     * @return float
     */
    public function getLastActivity()
    {
        return $this->container['lastActivity'];
    }

    /**
     * Sets lastActivity.
     *
     * @param float $lastActivity lastActivity
     *
     * @return self
     */
    public function setLastActivity($lastActivity)
    {
        if (is_null($lastActivity)) {
            throw new \InvalidArgumentException('non-nullable lastActivity cannot be null');
        }
        $this->container['lastActivity'] = $lastActivity;

        return $this;
    }

    /**
     * Gets id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id.
     *
     * @param string $id id
     *
     * @return self
     */
    public function setId($id)
    {
        if (is_null($id)) {
            throw new \InvalidArgumentException('non-nullable id cannot be null');
        }
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets createdAt.
     *
     * @return float
     */
    public function getCreatedAt()
    {
        return $this->container['createdAt'];
    }

    /**
     * Sets createdAt.
     *
     * @param float $createdAt createdAt
     *
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        if (is_null($createdAt)) {
            throw new \InvalidArgumentException('non-nullable createdAt cannot be null');
        }
        $this->container['createdAt'] = $createdAt;

        return $this;
    }

    /**
     * Gets updatedAt.
     *
     * @return float
     */
    public function getUpdatedAt()
    {
        return $this->container['updatedAt'];
    }

    /**
     * Sets updatedAt.
     *
     * @param float $updatedAt updatedAt
     *
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        if (is_null($updatedAt)) {
            throw new \InvalidArgumentException('non-nullable updatedAt cannot be null');
        }
        $this->container['updatedAt'] = $updatedAt;

        return $this;
    }

    /**
     * Gets deletedAt.
     *
     * @return null|float
     */
    public function getDeletedAt()
    {
        return $this->container['deletedAt'];
    }

    /**
     * Sets deletedAt.
     *
     * @param null|float $deletedAt deletedAt
     *
     * @return self
     */
    public function setDeletedAt($deletedAt)
    {
        if (is_null($deletedAt)) {
            array_push($this->openAPINullablesSetToNull, 'deletedAt');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('deletedAt', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['deletedAt'] = $deletedAt;

        return $this;
    }

    /**
     * Gets secret.
     *
     * @return null|string
     */
    public function getSecret()
    {
        return $this->container['secret'];
    }

    /**
     * Sets secret.
     *
     * @param null|string $secret secret
     *
     * @return self
     */
    public function setSecret($secret)
    {
        if (is_null($secret)) {
            array_push($this->openAPINullablesSetToNull, 'secret');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('secret', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['secret'] = $secret;

        return $this;
    }

    /**
     * Gets restUrls.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RegisterNewSiteDtoRestUrls
     */
    public function getRestUrls()
    {
        return $this->container['restUrls'];
    }

    /**
     * Sets restUrls.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RegisterNewSiteDtoRestUrls $restUrls restUrls
     *
     * @return self
     */
    public function setRestUrls($restUrls)
    {
        if (is_null($restUrls)) {
            throw new \InvalidArgumentException('non-nullable restUrls cannot be null');
        }
        $this->container['restUrls'] = $restUrls;

        return $this;
    }

    /**
     * Gets maxRequestsPerMinute.
     *
     * @return null|float
     */
    public function getMaxRequestsPerMinute()
    {
        return $this->container['maxRequestsPerMinute'];
    }

    /**
     * Sets maxRequestsPerMinute.
     *
     * @param null|float $maxRequestsPerMinute maxRequestsPerMinute
     *
     * @return self
     */
    public function setMaxRequestsPerMinute($maxRequestsPerMinute)
    {
        if (is_null($maxRequestsPerMinute)) {
            array_push($this->openAPINullablesSetToNull, 'maxRequestsPerMinute');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('maxRequestsPerMinute', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['maxRequestsPerMinute'] = $maxRequestsPerMinute;

        return $this;
    }

    /**
     * Gets maxParallelRequests.
     *
     * @return null|float
     */
    public function getMaxParallelRequests()
    {
        return $this->container['maxParallelRequests'];
    }

    /**
     * Sets maxParallelRequests.
     *
     * @param null|float $maxParallelRequests maxParallelRequests
     *
     * @return self
     */
    public function setMaxParallelRequests($maxParallelRequests)
    {
        if (is_null($maxParallelRequests)) {
            array_push($this->openAPINullablesSetToNull, 'maxParallelRequests');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('maxParallelRequests', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['maxParallelRequests'] = $maxParallelRequests;

        return $this;
    }

    /**
     * Gets notAvailableCounter.
     *
     * @return null|float
     */
    public function getNotAvailableCounter()
    {
        return $this->container['notAvailableCounter'];
    }

    /**
     * Sets notAvailableCounter.
     *
     * @param null|float $notAvailableCounter notAvailableCounter
     *
     * @return self
     */
    public function setNotAvailableCounter($notAvailableCounter)
    {
        if (is_null($notAvailableCounter)) {
            array_push($this->openAPINullablesSetToNull, 'notAvailableCounter');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('notAvailableCounter', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['notAvailableCounter'] = $notAvailableCounter;

        return $this;
    }

    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param int $offset Offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param int $offset Offset
     *
     * @return null|mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param null|int $offset Offset
     * @param mixed    $value  Value to be set
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param int $offset Offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     *
     * @see https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets a header-safe presentation of the object.
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }

    /**
     * Array of nullable properties.
     */
    protected static function openAPINullables(): array
    {
        return self::$openAPINullables;
    }

    /**
     * Array of nullable field names deliberately set to null.
     *
     * @return bool[]
     */
    private function getOpenAPINullablesSetToNull(): array
    {
        return $this->openAPINullablesSetToNull;
    }

    /**
     * Setter - Array of nullable field names deliberately set to null.
     *
     * @param bool[] $openAPINullablesSetToNull
     */
    private function setOpenAPINullablesSetToNull(array $openAPINullablesSetToNull): void
    {
        $this->openAPINullablesSetToNull = $openAPINullablesSetToNull;
    }

    /**
     * Sets $this->container[$variableName] to the given data or to the given default Value; if $variableName
     * is nullable and its value is set to null in the $fields array, then mark it as "set to null" in the
     * $this->openAPINullablesSetToNull array.
     *
     * @param mixed  $defaultValue
     */
    private function setIfExists(string $variableName, array $fields, $defaultValue): void
    {
        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }

        $this->container[$variableName] = $fields[$variableName] ?? $defaultValue;
    }
}
