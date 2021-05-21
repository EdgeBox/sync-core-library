<?php
/**
 * UsageSummary
 *
 * PHP version 7.2
 *
 * @category Class
 * @package  EdgeBox\SyncCore\V2\Raw
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Cloud Sync Core
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

use \ArrayAccess;
use \EdgeBox\SyncCore\V2\Raw\ObjectSerializer;

/**
 * UsageSummary Class Doc Comment
 *
 * @category Class
 * @package  EdgeBox\SyncCore\V2\Raw
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class UsageSummary implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'UsageSummary';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'contractMonthly' => 'float',
        'siteMonthly' => 'float',
        'siteDaily' => 'float',
        'siteHourly' => 'float'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'contractMonthly' => null,
        'siteMonthly' => null,
        'siteDaily' => null,
        'siteHourly' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'contractMonthly' => 'contractMonthly',
        'siteMonthly' => 'siteMonthly',
        'siteDaily' => 'siteDaily',
        'siteHourly' => 'siteHourly'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'contractMonthly' => 'setContractMonthly',
        'siteMonthly' => 'setSiteMonthly',
        'siteDaily' => 'setSiteDaily',
        'siteHourly' => 'setSiteHourly'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'contractMonthly' => 'getContractMonthly',
        'siteMonthly' => 'getSiteMonthly',
        'siteDaily' => 'getSiteDaily',
        'siteHourly' => 'getSiteHourly'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
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
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['contractMonthly'] = $data['contractMonthly'] ?? null;
        $this->container['siteMonthly'] = $data['siteMonthly'] ?? null;
        $this->container['siteDaily'] = $data['siteDaily'] ?? null;
        $this->container['siteHourly'] = $data['siteHourly'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['contractMonthly'] === null) {
            $invalidProperties[] = "'contractMonthly' can't be null";
        }
        if ($this->container['siteMonthly'] === null) {
            $invalidProperties[] = "'siteMonthly' can't be null";
        }
        if ($this->container['siteDaily'] === null) {
            $invalidProperties[] = "'siteDaily' can't be null";
        }
        if ($this->container['siteHourly'] === null) {
            $invalidProperties[] = "'siteHourly' can't be null";
        }
        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets contractMonthly
     *
     * @return float
     */
    public function getContractMonthly()
    {
        return $this->container['contractMonthly'];
    }

    /**
     * Sets contractMonthly
     *
     * @param float $contractMonthly contractMonthly
     *
     * @return self
     */
    public function setContractMonthly($contractMonthly)
    {
        $this->container['contractMonthly'] = $contractMonthly;

        return $this;
    }

    /**
     * Gets siteMonthly
     *
     * @return float
     */
    public function getSiteMonthly()
    {
        return $this->container['siteMonthly'];
    }

    /**
     * Sets siteMonthly
     *
     * @param float $siteMonthly siteMonthly
     *
     * @return self
     */
    public function setSiteMonthly($siteMonthly)
    {
        $this->container['siteMonthly'] = $siteMonthly;

        return $this;
    }

    /**
     * Gets siteDaily
     *
     * @return float
     */
    public function getSiteDaily()
    {
        return $this->container['siteDaily'];
    }

    /**
     * Sets siteDaily
     *
     * @param float $siteDaily siteDaily
     *
     * @return self
     */
    public function setSiteDaily($siteDaily)
    {
        $this->container['siteDaily'] = $siteDaily;

        return $this;
    }

    /**
     * Gets siteHourly
     *
     * @return float
     */
    public function getSiteHourly()
    {
        return $this->container['siteHourly'];
    }

    /**
     * Sets siteHourly
     *
     * @param float $siteHourly siteHourly
     *
     * @return self
     */
    public function setSiteHourly($siteHourly)
    {
        $this->container['siteHourly'] = $siteHourly;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
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
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    public function jsonSerialize()
    {
       return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets the string presentation of the object
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
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


