<?php
/**
 * FlowSyndication.
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
 *
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1-SNAPSHOT
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
 * FlowSyndication Class Doc Comment.
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
class FlowSyndication implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'FlowSyndication';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'mode' => '\EdgeBox\SyncCore\V2\Raw\Model\FlowSyndicationMode',
        'filters' => '\EdgeBox\SyncCore\V2\Raw\Model\FlowSyndicationFilter[]',
        'syndicateDeletions' => 'bool',
        'pool' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'entityTypes' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]',
        'entityTypeVersions' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static $openAPIFormats = [
        'mode' => null,
        'filters' => null,
        'syndicateDeletions' => null,
        'pool' => null,
        'entityTypes' => null,
        'entityTypeVersions' => null,
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
        'mode' => 'mode',
        'filters' => 'filters',
        'syndicateDeletions' => 'syndicateDeletions',
        'pool' => 'pool',
        'entityTypes' => 'entityTypes',
        'entityTypeVersions' => 'entityTypeVersions',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'mode' => 'setMode',
        'filters' => 'setFilters',
        'syndicateDeletions' => 'setSyndicateDeletions',
        'pool' => 'setPool',
        'entityTypes' => 'setEntityTypes',
        'entityTypeVersions' => 'setEntityTypeVersions',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'mode' => 'getMode',
        'filters' => 'getFilters',
        'syndicateDeletions' => 'getSyndicateDeletions',
        'pool' => 'getPool',
        'entityTypes' => 'getEntityTypes',
        'entityTypeVersions' => 'getEntityTypeVersions',
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
        $this->container['mode'] = $data['mode'] ?? null;
        $this->container['filters'] = $data['filters'] ?? null;
        $this->container['syndicateDeletions'] = $data['syndicateDeletions'] ?? null;
        $this->container['pool'] = $data['pool'] ?? null;
        $this->container['entityTypes'] = $data['entityTypes'] ?? null;
        $this->container['entityTypeVersions'] = $data['entityTypeVersions'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if (null === $this->container['mode']) {
            $invalidProperties[] = "'mode' can't be null";
        }
        if (null === $this->container['pool']) {
            $invalidProperties[] = "'pool' can't be null";
        }
        if (null === $this->container['entityTypes']) {
            $invalidProperties[] = "'entityTypes' can't be null";
        }
        if (null === $this->container['entityTypeVersions']) {
            $invalidProperties[] = "'entityTypeVersions' can't be null";
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
     * Gets mode.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\FlowSyndicationMode
     */
    public function getMode()
    {
        return $this->container['mode'];
    }

    /**
     * Sets mode.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\FlowSyndicationMode $mode mode
     *
     * @return self
     */
    public function setMode($mode)
    {
        $this->container['mode'] = $mode;

        return $this;
    }

    /**
     * Gets filters.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\FlowSyndicationFilter[]|null
     */
    public function getFilters()
    {
        return $this->container['filters'];
    }

    /**
     * Sets filters.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\FlowSyndicationFilter[]|null $filters filters
     *
     * @return self
     */
    public function setFilters($filters)
    {
        $this->container['filters'] = $filters;

        return $this;
    }

    /**
     * Gets syndicateDeletions.
     *
     * @return bool|null
     */
    public function getSyndicateDeletions()
    {
        return $this->container['syndicateDeletions'];
    }

    /**
     * Sets syndicateDeletions.
     *
     * @param bool|null $syndicateDeletions syndicateDeletions
     *
     * @return self
     */
    public function setSyndicateDeletions($syndicateDeletions)
    {
        $this->container['syndicateDeletions'] = $syndicateDeletions;

        return $this;
    }

    /**
     * Gets pool.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getPool()
    {
        return $this->container['pool'];
    }

    /**
     * Sets pool.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $pool pool
     *
     * @return self
     */
    public function setPool($pool)
    {
        $this->container['pool'] = $pool;

        return $this;
    }

    /**
     * Gets entityTypes.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]
     */
    public function getEntityTypes()
    {
        return $this->container['entityTypes'];
    }

    /**
     * Sets entityTypes.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[] $entityTypes entityTypes
     *
     * @return self
     */
    public function setEntityTypes($entityTypes)
    {
        $this->container['entityTypes'] = $entityTypes;

        return $this;
    }

    /**
     * Gets entityTypeVersions.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]
     */
    public function getEntityTypeVersions()
    {
        return $this->container['entityTypeVersions'];
    }

    /**
     * Sets entityTypeVersions.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[] $entityTypeVersions entityTypeVersions
     *
     * @return self
     */
    public function setEntityTypeVersions($entityTypeVersions)
    {
        $this->container['entityTypeVersions'] = $entityTypeVersions;

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
