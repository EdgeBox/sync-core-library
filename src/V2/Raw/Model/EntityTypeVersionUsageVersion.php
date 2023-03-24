<?php
/**
 * EntityTypeVersionUsageVersion.
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
 * EntityTypeVersionUsageVersion Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
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
     * Array of nullable properties. Used for (de)serialization.
     *
     * @var bool[]
     */
    protected static array $openAPINullables = [
        'versionId' => false,
        'sites' => false,
        'missingProperties' => false,
        'additionalProperties' => false,
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
        $this->setIfExists('versionId', $data ?? [], null);
        $this->setIfExists('sites', $data ?? [], null);
        $this->setIfExists('missingProperties', $data ?? [], null);
        $this->setIfExists('additionalProperties', $data ?? [], null);
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
        if (is_null($versionId)) {
            throw new \InvalidArgumentException('non-nullable versionId cannot be null');
        }
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
        if (is_null($sites)) {
            throw new \InvalidArgumentException('non-nullable sites cannot be null');
        }
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
        if (is_null($missingProperties)) {
            throw new \InvalidArgumentException('non-nullable missingProperties cannot be null');
        }
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
        if (is_null($additionalProperties)) {
            throw new \InvalidArgumentException('non-nullable additionalProperties cannot be null');
        }
        $this->container['additionalProperties'] = $additionalProperties;

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
