<?php
/**
 * EntityTypeVersionUsageVersion.
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
 * OpenAPI Generator version: 5.2.0-SNAPSHOT
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
 * EntityTypeVersionUsageVersion Class Doc Comment.
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
class EntityTypeVersionUsageVersion implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'EntityTypeVersionUsageVersion';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'versionId' => 'string',
        'sites' => '\EdgeBox\SyncCore\V2\Raw\Model\EntityTypeVersionUsageSite[]',
        'missingProperties' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeProperty[]',
        'additionalProperties' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeProperty[]',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static $openAPIFormats = [
        'versionId' => null,
        'sites' => null,
        'missingProperties' => null,
        'additionalProperties' => null,
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
        'versionId' => 'versionId',
        'sites' => 'sites',
        'missingProperties' => 'missingProperties',
        'additionalProperties' => 'additionalProperties',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'versionId' => 'setVersionId',
        'sites' => 'setSites',
        'missingProperties' => 'setMissingProperties',
        'additionalProperties' => 'setAdditionalProperties',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'versionId' => 'getVersionId',
        'sites' => 'getSites',
        'missingProperties' => 'getMissingProperties',
        'additionalProperties' => 'getAdditionalProperties',
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
        $this->container['versionId'] = $data['versionId'] ?? null;
        $this->container['sites'] = $data['sites'] ?? null;
        $this->container['missingProperties'] = $data['missingProperties'] ?? null;
        $this->container['additionalProperties'] = $data['additionalProperties'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if (null === $this->container['versionId']) {
            $invalidProperties[] = "'versionId' can't be null";
        }
        if (null === $this->container['sites']) {
            $invalidProperties[] = "'sites' can't be null";
        }
        if (null === $this->container['missingProperties']) {
            $invalidProperties[] = "'missingProperties' can't be null";
        }
        if (null === $this->container['additionalProperties']) {
            $invalidProperties[] = "'additionalProperties' can't be null";
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
     * Gets versionId.
     *
     * @return string
     */
    public function getVersionId()
    {
        return $this->container['versionId'];
    }

    /**
     * Sets versionId.
     *
     * @param string $versionId versionId
     *
     * @return self
     */
    public function setVersionId($versionId)
    {
        $this->container['versionId'] = $versionId;

        return $this;
    }

    /**
     * Gets sites.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\EntityTypeVersionUsageSite[]
     */
    public function getSites()
    {
        return $this->container['sites'];
    }

    /**
     * Sets sites.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\EntityTypeVersionUsageSite[] $sites sites
     *
     * @return self
     */
    public function setSites($sites)
    {
        $this->container['sites'] = $sites;

        return $this;
    }

    /**
     * Gets missingProperties.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeProperty[]
     */
    public function getMissingProperties()
    {
        return $this->container['missingProperties'];
    }

    /**
     * Sets missingProperties.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeProperty[] $missingProperties missingProperties
     *
     * @return self
     */
    public function setMissingProperties($missingProperties)
    {
        $this->container['missingProperties'] = $missingProperties;

        return $this;
    }

    /**
     * Gets additionalProperties.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeProperty[]
     */
    public function getAdditionalProperties()
    {
        return $this->container['additionalProperties'];
    }

    /**
     * Sets additionalProperties.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeProperty[] $additionalProperties additionalProperties
     *
     * @return self
     */
    public function setAdditionalProperties($additionalProperties)
    {
        $this->container['additionalProperties'] = $additionalProperties;

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
