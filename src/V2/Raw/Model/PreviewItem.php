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
        'previewHtml' => 'string',
        'localViewUrl' => 'string',
        'sourceViewUrl' => 'string',
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
        'previewHtml' => null,
        'localViewUrl' => null,
        'sourceViewUrl' => null,
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
        'entity' => 'entity',
        'previewHtml' => 'previewHtml',
        'localViewUrl' => 'localViewUrl',
        'sourceViewUrl' => 'sourceViewUrl',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'entity' => 'setEntity',
        'previewHtml' => 'setPreviewHtml',
        'localViewUrl' => 'setLocalViewUrl',
        'sourceViewUrl' => 'setSourceViewUrl',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'entity' => 'getEntity',
        'previewHtml' => 'getPreviewHtml',
        'localViewUrl' => 'getLocalViewUrl',
        'sourceViewUrl' => 'getSourceViewUrl',
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
        $this->container['entity'] = $data['entity'] ?? null;
        $this->container['previewHtml'] = $data['previewHtml'] ?? null;
        $this->container['localViewUrl'] = $data['localViewUrl'] ?? null;
        $this->container['sourceViewUrl'] = $data['sourceViewUrl'] ?? null;
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
        if (null === $this->container['previewHtml']) {
            $invalidProperties[] = "'previewHtml' can't be null";
        }
        if (null === $this->container['sourceViewUrl']) {
            $invalidProperties[] = "'sourceViewUrl' can't be null";
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
     * Gets previewHtml.
     *
     * @return string
     */
    public function getPreviewHtml()
    {
        return $this->container['previewHtml'];
    }

    /**
     * Sets previewHtml.
     *
     * @param string $previewHtml previewHtml
     *
     * @return self
     */
    public function setPreviewHtml($previewHtml)
    {
        $this->container['previewHtml'] = $previewHtml;

        return $this;
    }

    /**
     * Gets localViewUrl.
     *
     * @return string|null
     */
    public function getLocalViewUrl()
    {
        return $this->container['localViewUrl'];
    }

    /**
     * Sets localViewUrl.
     *
     * @param string|null $localViewUrl localViewUrl
     *
     * @return self
     */
    public function setLocalViewUrl($localViewUrl)
    {
        $this->container['localViewUrl'] = $localViewUrl;

        return $this;
    }

    /**
     * Gets sourceViewUrl.
     *
     * @return string
     */
    public function getSourceViewUrl()
    {
        return $this->container['sourceViewUrl'];
    }

    /**
     * Sets sourceViewUrl.
     *
     * @param string $sourceViewUrl sourceViewUrl
     *
     * @return self
     */
    public function setSourceViewUrl($sourceViewUrl)
    {
        $this->container['sourceViewUrl'] = $sourceViewUrl;

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
