<?php
/**
 * SyndicationErrorList.
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
 * SyndicationErrorList Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class SyndicationErrorList implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'SyndicationErrorList';

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
        'items' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationErrorSummary[]',
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
     * Array of nullable properties. Used for (de)serialization.
     *
     * @var bool[]
     */
    protected static array $openAPINullables = [
        'page' => false,
        'numberOfPages' => false,
        'itemsPerPage' => false,
        'totalNumberOfItems' => false,
        'items' => false,
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
        $this->setIfExists('page', $data ?? [], null);
        $this->setIfExists('numberOfPages', $data ?? [], null);
        $this->setIfExists('itemsPerPage', $data ?? [], null);
        $this->setIfExists('totalNumberOfItems', $data ?? [], null);
        $this->setIfExists('items', $data ?? [], null);
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
        if (is_null($page)) {
            throw new \InvalidArgumentException('non-nullable page cannot be null');
        }
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
        if (is_null($numberOfPages)) {
            throw new \InvalidArgumentException('non-nullable numberOfPages cannot be null');
        }
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
        if (is_null($itemsPerPage)) {
            throw new \InvalidArgumentException('non-nullable itemsPerPage cannot be null');
        }
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
        if (is_null($totalNumberOfItems)) {
            throw new \InvalidArgumentException('non-nullable totalNumberOfItems cannot be null');
        }
        $this->container['totalNumberOfItems'] = $totalNumberOfItems;

        return $this;
    }

    /**
     * Gets items.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SyndicationErrorSummary[]
     */
    public function getItems()
    {
        return $this->container['items'];
    }

    /**
     * Sets items.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SyndicationErrorSummary[] $items items
     *
     * @return self
     */
    public function setItems($items)
    {
        if (is_null($items)) {
            throw new \InvalidArgumentException('non-nullable items cannot be null');
        }
        $this->container['items'] = $items;

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
