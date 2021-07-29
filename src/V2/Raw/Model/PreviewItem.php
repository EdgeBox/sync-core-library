<?php
/**
 * PreviewItem.
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
 * PreviewItem Class Doc Comment.
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
class PreviewItem implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'PreviewItem';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'entity' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityRevisionEntity',
        'entityTypeVersion' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeVersionEntity',
        'previewHtml' => 'string',
        'localUsage' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityUsageEntity',
        'sourceUsage' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityUsageEntity',
        'lastPull' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationEntity',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static $openAPIFormats = [
        'entity' => null,
        'entityTypeVersion' => null,
        'previewHtml' => null,
        'localUsage' => null,
        'sourceUsage' => null,
        'lastPull' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'entity' => 'entity',
        'entityTypeVersion' => 'entityTypeVersion',
        'previewHtml' => 'previewHtml',
        'localUsage' => 'localUsage',
        'sourceUsage' => 'sourceUsage',
        'lastPull' => 'lastPull',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'entity' => 'setEntity',
        'entityTypeVersion' => 'setEntityTypeVersion',
        'previewHtml' => 'setPreviewHtml',
        'localUsage' => 'setLocalUsage',
        'sourceUsage' => 'setSourceUsage',
        'lastPull' => 'setLastPull',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'entity' => 'getEntity',
        'entityTypeVersion' => 'getEntityTypeVersion',
        'previewHtml' => 'getPreviewHtml',
        'localUsage' => 'getLocalUsage',
        'sourceUsage' => 'getSourceUsage',
        'lastPull' => 'getLastPull',
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
        $this->container['entity'] = $data['entity'] ?? null;
        $this->container['entityTypeVersion'] = $data['entityTypeVersion'] ?? null;
        $this->container['previewHtml'] = $data['previewHtml'] ?? null;
        $this->container['localUsage'] = $data['localUsage'] ?? null;
        $this->container['sourceUsage'] = $data['sourceUsage'] ?? null;
        $this->container['lastPull'] = $data['lastPull'] ?? null;
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

        if (null === $this->container['entity']) {
            $invalidProperties[] = "'entity' can't be null";
        }
        if (null === $this->container['entityTypeVersion']) {
            $invalidProperties[] = "'entityTypeVersion' can't be null";
        }
        if (null === $this->container['sourceUsage']) {
            $invalidProperties[] = "'sourceUsage' can't be null";
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
     * Gets entity.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityRevisionEntity
     */
    public function getEntity()
    {
        return $this->container['entity'];
    }

    /**
     * Sets entity.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityRevisionEntity $entity entity
     *
     * @return self
     */
    public function setEntity($entity)
    {
        $this->container['entity'] = $entity;

        return $this;
    }

    /**
     * Gets entityTypeVersion.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeVersionEntity
     */
    public function getEntityTypeVersion()
    {
        return $this->container['entityTypeVersion'];
    }

    /**
     * Sets entityTypeVersion.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeVersionEntity $entityTypeVersion entityTypeVersion
     *
     * @return self
     */
    public function setEntityTypeVersion($entityTypeVersion)
    {
        $this->container['entityTypeVersion'] = $entityTypeVersion;

        return $this;
    }

    /**
     * Gets previewHtml.
     *
     * @return null|string
     */
    public function getPreviewHtml()
    {
        return $this->container['previewHtml'];
    }

    /**
     * Sets previewHtml.
     *
     * @param null|string $previewHtml previewHtml
     *
     * @return self
     */
    public function setPreviewHtml($previewHtml)
    {
        $this->container['previewHtml'] = $previewHtml;

        return $this;
    }

    /**
     * Gets localUsage.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityUsageEntity
     */
    public function getLocalUsage()
    {
        return $this->container['localUsage'];
    }

    /**
     * Sets localUsage.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityUsageEntity $localUsage localUsage
     *
     * @return self
     */
    public function setLocalUsage($localUsage)
    {
        $this->container['localUsage'] = $localUsage;

        return $this;
    }

    /**
     * Gets sourceUsage.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityUsageEntity
     */
    public function getSourceUsage()
    {
        return $this->container['sourceUsage'];
    }

    /**
     * Sets sourceUsage.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityUsageEntity $sourceUsage sourceUsage
     *
     * @return self
     */
    public function setSourceUsage($sourceUsage)
    {
        $this->container['sourceUsage'] = $sourceUsage;

        return $this;
    }

    /**
     * Gets lastPull.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\SyndicationEntity
     */
    public function getLastPull()
    {
        return $this->container['lastPull'];
    }

    /**
     * Sets lastPull.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\SyndicationEntity $lastPull lastPull
     *
     * @return self
     */
    public function setLastPull($lastPull)
    {
        $this->container['lastPull'] = $lastPull;

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
     *               of any type other than a resource
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