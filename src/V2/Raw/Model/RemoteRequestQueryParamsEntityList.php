<?php
/**
 * RemoteRequestQueryParamsEntityList.
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
 * RemoteRequestQueryParamsEntityList Class Doc Comment.
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
class RemoteRequestQueryParamsEntityList implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'RemoteRequestQueryParamsEntityList';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'page' => 'float',
        'itemsPerPage' => 'float',
        'mode' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityListRequestMode',
        'changedAfter' => 'float',
        'namespaceMachineName' => 'string',
        'machineName' => 'string',
        'versionId' => 'string',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static $openAPIFormats = [
        'page' => null,
        'itemsPerPage' => null,
        'mode' => null,
        'changedAfter' => null,
        'namespaceMachineName' => null,
        'machineName' => null,
        'versionId' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'page' => 'page',
        'itemsPerPage' => 'itemsPerPage',
        'mode' => 'mode',
        'changedAfter' => 'changedAfter',
        'namespaceMachineName' => 'namespaceMachineName',
        'machineName' => 'machineName',
        'versionId' => 'versionId',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'page' => 'setPage',
        'itemsPerPage' => 'setItemsPerPage',
        'mode' => 'setMode',
        'changedAfter' => 'setChangedAfter',
        'namespaceMachineName' => 'setNamespaceMachineName',
        'machineName' => 'setMachineName',
        'versionId' => 'setVersionId',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'page' => 'getPage',
        'itemsPerPage' => 'getItemsPerPage',
        'mode' => 'getMode',
        'changedAfter' => 'getChangedAfter',
        'namespaceMachineName' => 'getNamespaceMachineName',
        'machineName' => 'getMachineName',
        'versionId' => 'getVersionId',
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
        $this->container['page'] = $data['page'] ?? null;
        $this->container['itemsPerPage'] = $data['itemsPerPage'] ?? null;
        $this->container['mode'] = $data['mode'] ?? null;
        $this->container['changedAfter'] = $data['changedAfter'] ?? null;
        $this->container['namespaceMachineName'] = $data['namespaceMachineName'] ?? null;
        $this->container['machineName'] = $data['machineName'] ?? null;
        $this->container['versionId'] = $data['versionId'] ?? null;
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

        if (null === $this->container['mode']) {
            $invalidProperties[] = "'mode' can't be null";
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
     * Gets page.
     *
     * @return null|float
     */
    public function getPage()
    {
        return $this->container['page'];
    }

    /**
     * Sets page.
     *
     * @param null|float $page page
     *
     * @return self
     */
    public function setPage($page)
    {
        $this->container['page'] = $page;

        return $this;
    }

    /**
     * Gets itemsPerPage.
     *
     * @return null|float
     */
    public function getItemsPerPage()
    {
        return $this->container['itemsPerPage'];
    }

    /**
     * Sets itemsPerPage.
     *
     * @param null|float $itemsPerPage itemsPerPage
     *
     * @return self
     */
    public function setItemsPerPage($itemsPerPage)
    {
        $this->container['itemsPerPage'] = $itemsPerPage;

        return $this;
    }

    /**
     * Gets mode.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityListRequestMode
     */
    public function getMode()
    {
        return $this->container['mode'];
    }

    /**
     * Sets mode.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityListRequestMode $mode mode
     *
     * @return self
     */
    public function setMode($mode)
    {
        $this->container['mode'] = $mode;

        return $this;
    }

    /**
     * Gets changedAfter.
     *
     * @return null|float
     */
    public function getChangedAfter()
    {
        return $this->container['changedAfter'];
    }

    /**
     * Sets changedAfter.
     *
     * @param null|float $changedAfter changedAfter
     *
     * @return self
     */
    public function setChangedAfter($changedAfter)
    {
        $this->container['changedAfter'] = $changedAfter;

        return $this;
    }

    /**
     * Gets namespaceMachineName.
     *
     * @return null|string
     */
    public function getNamespaceMachineName()
    {
        return $this->container['namespaceMachineName'];
    }

    /**
     * Sets namespaceMachineName.
     *
     * @param null|string $namespaceMachineName namespaceMachineName
     *
     * @return self
     */
    public function setNamespaceMachineName($namespaceMachineName)
    {
        $this->container['namespaceMachineName'] = $namespaceMachineName;

        return $this;
    }

    /**
     * Gets machineName.
     *
     * @return null|string
     */
    public function getMachineName()
    {
        return $this->container['machineName'];
    }

    /**
     * Sets machineName.
     *
     * @param null|string $machineName machineName
     *
     * @return self
     */
    public function setMachineName($machineName)
    {
        $this->container['machineName'] = $machineName;

        return $this;
    }

    /**
     * Gets versionId.
     *
     * @return null|string
     */
    public function getVersionId()
    {
        return $this->container['versionId'];
    }

    /**
     * Sets versionId.
     *
     * @param null|string $versionId versionId
     *
     * @return self
     */
    public function setVersionId($versionId)
    {
        $this->container['versionId'] = $versionId;

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