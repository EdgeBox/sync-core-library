<?php
/**
 * PagedRemoteEntityRevisionList.
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
 * PagedRemoteEntityRevisionList Class Doc Comment.
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
class PagedRemoteEntityRevisionList implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'PagedRemoteEntityRevisionList';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'page' => 'float',
        'numberOfPages' => 'float',
        'itemsPerPage' => 'float',
        'totalNumberOfItems' => 'float',
        'items' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityRevisionEntity[]',
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
        'numberOfPages' => null,
        'itemsPerPage' => null,
        'totalNumberOfItems' => null,
        'items' => null,
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
        'page' => 'page',
        'numberOfPages' => 'numberOfPages',
        'itemsPerPage' => 'itemsPerPage',
        'totalNumberOfItems' => 'totalNumberOfItems',
        'items' => 'items',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'page' => 'setPage',
        'numberOfPages' => 'setNumberOfPages',
        'itemsPerPage' => 'setItemsPerPage',
        'totalNumberOfItems' => 'setTotalNumberOfItems',
        'items' => 'setItems',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'page' => 'getPage',
        'numberOfPages' => 'getNumberOfPages',
        'itemsPerPage' => 'getItemsPerPage',
        'totalNumberOfItems' => 'getTotalNumberOfItems',
        'items' => 'getItems',
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
        $this->container['page'] = $data['page'] ?? null;
        $this->container['numberOfPages'] = $data['numberOfPages'] ?? null;
        $this->container['itemsPerPage'] = $data['itemsPerPage'] ?? null;
        $this->container['totalNumberOfItems'] = $data['totalNumberOfItems'] ?? null;
        $this->container['items'] = $data['items'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if (null === $this->container['page']) {
            $invalidProperties[] = "'page' can't be null";
        }
        if (null === $this->container['numberOfPages']) {
            $invalidProperties[] = "'numberOfPages' can't be null";
        }
        if (null === $this->container['itemsPerPage']) {
            $invalidProperties[] = "'itemsPerPage' can't be null";
        }
        if (null === $this->container['totalNumberOfItems']) {
            $invalidProperties[] = "'totalNumberOfItems' can't be null";
        }
        if (null === $this->container['items']) {
            $invalidProperties[] = "'items' can't be null";
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
     * @return float
     */
    public function getPage()
    {
        return $this->container['page'];
    }

    /**
     * Sets page.
     *
     * @param float $page page
     *
     * @return self
     */
    public function setPage($page)
    {
        $this->container['page'] = $page;

        return $this;
    }

    /**
     * Gets numberOfPages.
     *
     * @return float
     */
    public function getNumberOfPages()
    {
        return $this->container['numberOfPages'];
    }

    /**
     * Sets numberOfPages.
     *
     * @param float $numberOfPages numberOfPages
     *
     * @return self
     */
    public function setNumberOfPages($numberOfPages)
    {
        $this->container['numberOfPages'] = $numberOfPages;

        return $this;
    }

    /**
     * Gets itemsPerPage.
     *
     * @return float
     */
    public function getItemsPerPage()
    {
        return $this->container['itemsPerPage'];
    }

    /**
     * Sets itemsPerPage.
     *
     * @param float $itemsPerPage itemsPerPage
     *
     * @return self
     */
    public function setItemsPerPage($itemsPerPage)
    {
        $this->container['itemsPerPage'] = $itemsPerPage;

        return $this;
    }

    /**
     * Gets totalNumberOfItems.
     *
     * @return float
     */
    public function getTotalNumberOfItems()
    {
        return $this->container['totalNumberOfItems'];
    }

    /**
     * Sets totalNumberOfItems.
     *
     * @param float $totalNumberOfItems totalNumberOfItems
     *
     * @return self
     */
    public function setTotalNumberOfItems($totalNumberOfItems)
    {
        $this->container['totalNumberOfItems'] = $totalNumberOfItems;

        return $this;
    }

    /**
     * Gets items.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityRevisionEntity[]
     */
    public function getItems()
    {
        return $this->container['items'];
    }

    /**
     * Sets items.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityRevisionEntity[] $items items
     *
     * @return self
     */
    public function setItems($items)
    {
        $this->container['items'] = $items;

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
