<?php
/**
 * RemoteEntityUsageEntity.
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
 * RemoteEntityUsageEntity Class Doc Comment.
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
class RemoteEntityUsageEntity implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'RemoteEntityUsageEntity';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'match' => '\EdgeBox\SyncCore\V2\Raw\Model\EntityMatchType',
        'status' => '\EdgeBox\SyncCore\V2\Raw\Model\EntityRemoteStatus',
        'viewUrl' => 'string',
        'site' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'translations' => '\EdgeBox\SyncCore\V2\Raw\Model\SyncCoreRemoteEntityUsageEntityReference[]',
        'poolMachineNames' => 'string[]',
        'entityType' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'remoteUuid' => 'string',
        'remoteUniqueId' => 'string',
        'lastPush' => 'float',
        'lastPull' => 'float',
        'customer' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'project' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'pools' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]',
        'id' => 'string',
        'createdAt' => 'float',
        'updatedAt' => 'float',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static $openAPIFormats = [
        'match' => null,
        'status' => null,
        'viewUrl' => null,
        'site' => null,
        'translations' => null,
        'poolMachineNames' => null,
        'entityType' => null,
        'remoteUuid' => null,
        'remoteUniqueId' => null,
        'lastPush' => null,
        'lastPull' => null,
        'customer' => null,
        'project' => null,
        'pools' => null,
        'id' => null,
        'createdAt' => null,
        'updatedAt' => null,
    ];

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
     * @var string[]
     */
    protected static $attributeMap = [
        'match' => 'match',
        'status' => 'status',
        'viewUrl' => 'viewUrl',
        'site' => 'site',
        'translations' => 'translations',
        'poolMachineNames' => 'poolMachineNames',
        'entityType' => 'entityType',
        'remoteUuid' => 'remoteUuid',
        'remoteUniqueId' => 'remoteUniqueId',
        'lastPush' => 'lastPush',
        'lastPull' => 'lastPull',
        'customer' => 'customer',
        'project' => 'project',
        'pools' => 'pools',
        'id' => 'id',
        'createdAt' => 'createdAt',
        'updatedAt' => 'updatedAt',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'match' => 'setMatch',
        'status' => 'setStatus',
        'viewUrl' => 'setViewUrl',
        'site' => 'setSite',
        'translations' => 'setTranslations',
        'poolMachineNames' => 'setPoolMachineNames',
        'entityType' => 'setEntityType',
        'remoteUuid' => 'setRemoteUuid',
        'remoteUniqueId' => 'setRemoteUniqueId',
        'lastPush' => 'setLastPush',
        'lastPull' => 'setLastPull',
        'customer' => 'setCustomer',
        'project' => 'setProject',
        'pools' => 'setPools',
        'id' => 'setId',
        'createdAt' => 'setCreatedAt',
        'updatedAt' => 'setUpdatedAt',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'match' => 'getMatch',
        'status' => 'getStatus',
        'viewUrl' => 'getViewUrl',
        'site' => 'getSite',
        'translations' => 'getTranslations',
        'poolMachineNames' => 'getPoolMachineNames',
        'entityType' => 'getEntityType',
        'remoteUuid' => 'getRemoteUuid',
        'remoteUniqueId' => 'getRemoteUniqueId',
        'lastPush' => 'getLastPush',
        'lastPull' => 'getLastPull',
        'customer' => 'getCustomer',
        'project' => 'getProject',
        'pools' => 'getPools',
        'id' => 'getId',
        'createdAt' => 'getCreatedAt',
        'updatedAt' => 'getUpdatedAt',
    ];

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
        $this->container['match'] = $data['match'] ?? null;
        $this->container['status'] = $data['status'] ?? null;
        $this->container['viewUrl'] = $data['viewUrl'] ?? null;
        $this->container['site'] = $data['site'] ?? null;
        $this->container['translations'] = $data['translations'] ?? null;
        $this->container['poolMachineNames'] = $data['poolMachineNames'] ?? null;
        $this->container['entityType'] = $data['entityType'] ?? null;
        $this->container['remoteUuid'] = $data['remoteUuid'] ?? null;
        $this->container['remoteUniqueId'] = $data['remoteUniqueId'] ?? null;
        $this->container['lastPush'] = $data['lastPush'] ?? null;
        $this->container['lastPull'] = $data['lastPull'] ?? null;
        $this->container['customer'] = $data['customer'] ?? null;
        $this->container['project'] = $data['project'] ?? null;
        $this->container['pools'] = $data['pools'] ?? null;
        $this->container['id'] = $data['id'] ?? null;
        $this->container['createdAt'] = $data['createdAt'] ?? null;
        $this->container['updatedAt'] = $data['updatedAt'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if (null === $this->container['match']) {
            $invalidProperties[] = "'match' can't be null";
        }
        if (null === $this->container['status']) {
            $invalidProperties[] = "'status' can't be null";
        }
        if (null === $this->container['viewUrl']) {
            $invalidProperties[] = "'viewUrl' can't be null";
        }
        if (null === $this->container['site']) {
            $invalidProperties[] = "'site' can't be null";
        }
        if (null === $this->container['translations']) {
            $invalidProperties[] = "'translations' can't be null";
        }
        if (null === $this->container['poolMachineNames']) {
            $invalidProperties[] = "'poolMachineNames' can't be null";
        }
        if (null === $this->container['entityType']) {
            $invalidProperties[] = "'entityType' can't be null";
        }
        if (null === $this->container['remoteUuid']) {
            $invalidProperties[] = "'remoteUuid' can't be null";
        }
        if (null === $this->container['customer']) {
            $invalidProperties[] = "'customer' can't be null";
        }
        if (null === $this->container['project']) {
            $invalidProperties[] = "'project' can't be null";
        }
        if (null === $this->container['pools']) {
            $invalidProperties[] = "'pools' can't be null";
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
     * Gets match.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\EntityMatchType
     */
    public function getMatch()
    {
        return $this->container['match'];
    }

    /**
     * Sets match.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\EntityMatchType $match match
     *
     * @return self
     */
    public function setMatch($match)
    {
        $this->container['match'] = $match;

        return $this;
    }

    /**
     * Gets status.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\EntityRemoteStatus
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\EntityRemoteStatus $status status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets viewUrl.
     *
     * @return string
     */
    public function getViewUrl()
    {
        return $this->container['viewUrl'];
    }

    /**
     * Sets viewUrl.
     *
     * @param string $viewUrl viewUrl
     *
     * @return self
     */
    public function setViewUrl($viewUrl)
    {
        $this->container['viewUrl'] = $viewUrl;

        return $this;
    }

    /**
     * Gets site.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getSite()
    {
        return $this->container['site'];
    }

    /**
     * Sets site.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $site site
     *
     * @return self
     */
    public function setSite($site)
    {
        $this->container['site'] = $site;

        return $this;
    }

    /**
     * Gets translations.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SyncCoreRemoteEntityUsageEntityReference[]
     */
    public function getTranslations()
    {
        return $this->container['translations'];
    }

    /**
     * Sets translations.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SyncCoreRemoteEntityUsageEntityReference[] $translations translations
     *
     * @return self
     */
    public function setTranslations($translations)
    {
        $this->container['translations'] = $translations;

        return $this;
    }

    /**
     * Gets poolMachineNames.
     *
     * @return string[]
     */
    public function getPoolMachineNames()
    {
        return $this->container['poolMachineNames'];
    }

    /**
     * Sets poolMachineNames.
     *
     * @param string[] $poolMachineNames poolMachineNames
     *
     * @return self
     */
    public function setPoolMachineNames($poolMachineNames)
    {
        $this->container['poolMachineNames'] = $poolMachineNames;

        return $this;
    }

    /**
     * Gets entityType.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getEntityType()
    {
        return $this->container['entityType'];
    }

    /**
     * Sets entityType.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $entityType entityType
     *
     * @return self
     */
    public function setEntityType($entityType)
    {
        $this->container['entityType'] = $entityType;

        return $this;
    }

    /**
     * Gets remoteUuid.
     *
     * @return string
     */
    public function getRemoteUuid()
    {
        return $this->container['remoteUuid'];
    }

    /**
     * Sets remoteUuid.
     *
     * @param string $remoteUuid remoteUuid
     *
     * @return self
     */
    public function setRemoteUuid($remoteUuid)
    {
        $this->container['remoteUuid'] = $remoteUuid;

        return $this;
    }

    /**
     * Gets remoteUniqueId.
     *
     * @return string|null
     */
    public function getRemoteUniqueId()
    {
        return $this->container['remoteUniqueId'];
    }

    /**
     * Sets remoteUniqueId.
     *
     * @param string|null $remoteUniqueId remoteUniqueId
     *
     * @return self
     */
    public function setRemoteUniqueId($remoteUniqueId)
    {
        $this->container['remoteUniqueId'] = $remoteUniqueId;

        return $this;
    }

    /**
     * Gets lastPush.
     *
     * @return float|null
     */
    public function getLastPush()
    {
        return $this->container['lastPush'];
    }

    /**
     * Sets lastPush.
     *
     * @param float|null $lastPush lastPush
     *
     * @return self
     */
    public function setLastPush($lastPush)
    {
        $this->container['lastPush'] = $lastPush;

        return $this;
    }

    /**
     * Gets lastPull.
     *
     * @return float|null
     */
    public function getLastPull()
    {
        return $this->container['lastPull'];
    }

    /**
     * Sets lastPull.
     *
     * @param float|null $lastPull lastPull
     *
     * @return self
     */
    public function setLastPull($lastPull)
    {
        $this->container['lastPull'] = $lastPull;

        return $this;
    }

    /**
     * Gets customer.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getCustomer()
    {
        return $this->container['customer'];
    }

    /**
     * Sets customer.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $customer customer
     *
     * @return self
     */
    public function setCustomer($customer)
    {
        $this->container['customer'] = $customer;

        return $this;
    }

    /**
     * Gets project.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getProject()
    {
        return $this->container['project'];
    }

    /**
     * Sets project.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $project project
     *
     * @return self
     */
    public function setProject($project)
    {
        $this->container['project'] = $project;

        return $this;
    }

    /**
     * Gets pools.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]
     */
    public function getPools()
    {
        return $this->container['pools'];
    }

    /**
     * Sets pools.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[] $pools pools
     *
     * @return self
     */
    public function setPools($pools)
    {
        $this->container['pools'] = $pools;

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
        $this->container['updatedAt'] = $updatedAt;

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
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int|null $offset Offset
     * @param mixed    $value  Value to be set
     *
     * @return void
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
     *
     * @return void
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
     *               of any type other than a resource
     */
    public function jsonSerialize()
    {
        return ObjectSerializer::sanitizeForSerialization($this);
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
     * Gets a header-safe presentation of the object.
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}
