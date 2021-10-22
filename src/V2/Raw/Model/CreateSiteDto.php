<?php
/**
 * CreateSiteDto.
 *
 * PHP version 7.2
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 */

/**
 * Cloud Sync Core.
 *
 * The Sync Core.
 *
 * The version of the OpenAPI document: 1.0
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.2.0
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
 * CreateSiteDto Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null
 */
class CreateSiteDto implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'CreateSiteDto';

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
        'customer' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'contract' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'project' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'lastActivity' => 'float',
        'uuid' => 'string',
        'environmentType' => '\EdgeBox\SyncCore\V2\Raw\Model\SiteEnvironmentType',
        'secret' => 'string',
        'restUrls' => '\EdgeBox\SyncCore\V2\Raw\Model\SiteRestUrls',
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
        'customer' => null,
        'contract' => null,
        'project' => null,
        'lastActivity' => null,
        'uuid' => null,
        'environmentType' => null,
        'secret' => null,
        'restUrls' => null,
    ];

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
        'customer' => 'customer',
        'contract' => 'contract',
        'project' => 'project',
        'lastActivity' => 'lastActivity',
        'uuid' => 'uuid',
        'environmentType' => 'environmentType',
        'secret' => 'secret',
        'restUrls' => 'restUrls',
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
        'customer' => 'setCustomer',
        'contract' => 'setContract',
        'project' => 'setProject',
        'lastActivity' => 'setLastActivity',
        'uuid' => 'setUuid',
        'environmentType' => 'setEnvironmentType',
        'secret' => 'setSecret',
        'restUrls' => 'setRestUrls',
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
        'customer' => 'getCustomer',
        'contract' => 'getContract',
        'project' => 'getProject',
        'lastActivity' => 'getLastActivity',
        'uuid' => 'getUuid',
        'environmentType' => 'getEnvironmentType',
        'secret' => 'getSecret',
        'restUrls' => 'getRestUrls',
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
        $this->container['name'] = $data['name'] ?? null;
        $this->container['deprecatedMachineName'] = $data['deprecatedMachineName'] ?? null;
        $this->container['baseUrl'] = $data['baseUrl'] ?? null;
        $this->container['status'] = $data['status'] ?? null;
        $this->container['inactiveSince'] = $data['inactiveSince'] ?? null;
        $this->container['appType'] = $data['appType'] ?? null;
        $this->container['appVersion'] = $data['appVersion'] ?? null;
        $this->container['appModuleVersion'] = $data['appModuleVersion'] ?? null;
        $this->container['useProxy'] = $data['useProxy'] ?? null;
        $this->container['customer'] = $data['customer'] ?? null;
        $this->container['contract'] = $data['contract'] ?? null;
        $this->container['project'] = $data['project'] ?? null;
        $this->container['lastActivity'] = $data['lastActivity'] ?? null;
        $this->container['uuid'] = $data['uuid'] ?? null;
        $this->container['environmentType'] = $data['environmentType'] ?? null;
        $this->container['secret'] = $data['secret'] ?? null;
        $this->container['restUrls'] = $data['restUrls'] ?? null;
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
        $this->container['useProxy'] = $useProxy;

        return $this;
    }

    /**
     * Gets customer.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getCustomer()
    {
        return $this->container['customer'];
    }

    /**
     * Sets customer.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $customer customer
     *
     * @return self
     */
    public function setCustomer($customer)
    {
        $this->container['customer'] = $customer;

        return $this;
    }

    /**
     * Gets contract.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getContract()
    {
        return $this->container['contract'];
    }

    /**
     * Sets contract.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $contract contract
     *
     * @return self
     */
    public function setContract($contract)
    {
        $this->container['contract'] = $contract;

        return $this;
    }

    /**
     * Gets project.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getProject()
    {
        return $this->container['project'];
    }

    /**
     * Sets project.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $project project
     *
     * @return self
     */
    public function setProject($project)
    {
        $this->container['project'] = $project;

        return $this;
    }

    /**
     * Gets lastActivity.
     *
     * @return null|float
     */
    public function getLastActivity()
    {
        return $this->container['lastActivity'];
    }

    /**
     * Sets lastActivity.
     *
     * @param null|float $lastActivity lastActivity
     *
     * @return self
     */
    public function setLastActivity($lastActivity)
    {
        $this->container['lastActivity'] = $lastActivity;

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
        $this->container['environmentType'] = $environmentType;

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
        $this->container['secret'] = $secret;

        return $this;
    }

    /**
     * Gets restUrls.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SiteRestUrls
     */
    public function getRestUrls()
    {
        return $this->container['restUrls'];
    }

    /**
     * Sets restUrls.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SiteRestUrls $restUrls restUrls
     *
     * @return self
     */
    public function setRestUrls($restUrls)
    {
        $this->container['restUrls'] = $restUrls;

        return $this;
    }

    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param int $offset Offset
     *
     * @return bool
     */
    public function offsetExists($offset)
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
    public function offsetSet($offset, $value)
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
    public function offsetUnset($offset)
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
}
