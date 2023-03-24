<?php
/**
 * SetThrottlingDto.
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
 * SetThrottlingDto Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class SetThrottlingDto implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'SetThrottlingDto';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'projectDefaultPerSitePerMinute' => 'float',
        'projectDefaultPerSiteParallel' => 'float',
        'projectPerMinute' => 'float',
        'projectParallel' => 'float',
        'sitePerMinute' => 'float',
        'siteParallel' => 'float',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static $openAPIFormats = [
        'projectDefaultPerSitePerMinute' => null,
        'projectDefaultPerSiteParallel' => null,
        'projectPerMinute' => null,
        'projectParallel' => null,
        'sitePerMinute' => null,
        'siteParallel' => null,
    ];

    /**
     * Array of nullable properties. Used for (de)serialization.
     *
     * @var bool[]
     */
    protected static array $openAPINullables = [
        'projectDefaultPerSitePerMinute' => true,
        'projectDefaultPerSiteParallel' => true,
        'projectPerMinute' => true,
        'projectParallel' => true,
        'sitePerMinute' => true,
        'siteParallel' => true,
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
        'projectDefaultPerSitePerMinute' => 'projectDefaultPerSitePerMinute',
        'projectDefaultPerSiteParallel' => 'projectDefaultPerSiteParallel',
        'projectPerMinute' => 'projectPerMinute',
        'projectParallel' => 'projectParallel',
        'sitePerMinute' => 'sitePerMinute',
        'siteParallel' => 'siteParallel',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'projectDefaultPerSitePerMinute' => 'setProjectDefaultPerSitePerMinute',
        'projectDefaultPerSiteParallel' => 'setProjectDefaultPerSiteParallel',
        'projectPerMinute' => 'setProjectPerMinute',
        'projectParallel' => 'setProjectParallel',
        'sitePerMinute' => 'setSitePerMinute',
        'siteParallel' => 'setSiteParallel',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'projectDefaultPerSitePerMinute' => 'getProjectDefaultPerSitePerMinute',
        'projectDefaultPerSiteParallel' => 'getProjectDefaultPerSiteParallel',
        'projectPerMinute' => 'getProjectPerMinute',
        'projectParallel' => 'getProjectParallel',
        'sitePerMinute' => 'getSitePerMinute',
        'siteParallel' => 'getSiteParallel',
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
        $this->setIfExists('projectDefaultPerSitePerMinute', $data ?? [], null);
        $this->setIfExists('projectDefaultPerSiteParallel', $data ?? [], null);
        $this->setIfExists('projectPerMinute', $data ?? [], null);
        $this->setIfExists('projectParallel', $data ?? [], null);
        $this->setIfExists('sitePerMinute', $data ?? [], null);
        $this->setIfExists('siteParallel', $data ?? [], null);
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
        return [];
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
     * Gets projectDefaultPerSitePerMinute.
     *
     * @return null|float
     */
    public function getProjectDefaultPerSitePerMinute()
    {
        return $this->container['projectDefaultPerSitePerMinute'];
    }

    /**
     * Sets projectDefaultPerSitePerMinute.
     *
     * @param null|float $projectDefaultPerSitePerMinute projectDefaultPerSitePerMinute
     *
     * @return self
     */
    public function setProjectDefaultPerSitePerMinute($projectDefaultPerSitePerMinute)
    {
        if (is_null($projectDefaultPerSitePerMinute)) {
            array_push($this->openAPINullablesSetToNull, 'projectDefaultPerSitePerMinute');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('projectDefaultPerSitePerMinute', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['projectDefaultPerSitePerMinute'] = $projectDefaultPerSitePerMinute;

        return $this;
    }

    /**
     * Gets projectDefaultPerSiteParallel.
     *
     * @return null|float
     */
    public function getProjectDefaultPerSiteParallel()
    {
        return $this->container['projectDefaultPerSiteParallel'];
    }

    /**
     * Sets projectDefaultPerSiteParallel.
     *
     * @param null|float $projectDefaultPerSiteParallel projectDefaultPerSiteParallel
     *
     * @return self
     */
    public function setProjectDefaultPerSiteParallel($projectDefaultPerSiteParallel)
    {
        if (is_null($projectDefaultPerSiteParallel)) {
            array_push($this->openAPINullablesSetToNull, 'projectDefaultPerSiteParallel');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('projectDefaultPerSiteParallel', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['projectDefaultPerSiteParallel'] = $projectDefaultPerSiteParallel;

        return $this;
    }

    /**
     * Gets projectPerMinute.
     *
     * @return null|float
     */
    public function getProjectPerMinute()
    {
        return $this->container['projectPerMinute'];
    }

    /**
     * Sets projectPerMinute.
     *
     * @param null|float $projectPerMinute projectPerMinute
     *
     * @return self
     */
    public function setProjectPerMinute($projectPerMinute)
    {
        if (is_null($projectPerMinute)) {
            array_push($this->openAPINullablesSetToNull, 'projectPerMinute');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('projectPerMinute', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['projectPerMinute'] = $projectPerMinute;

        return $this;
    }

    /**
     * Gets projectParallel.
     *
     * @return null|float
     */
    public function getProjectParallel()
    {
        return $this->container['projectParallel'];
    }

    /**
     * Sets projectParallel.
     *
     * @param null|float $projectParallel projectParallel
     *
     * @return self
     */
    public function setProjectParallel($projectParallel)
    {
        if (is_null($projectParallel)) {
            array_push($this->openAPINullablesSetToNull, 'projectParallel');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('projectParallel', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['projectParallel'] = $projectParallel;

        return $this;
    }

    /**
     * Gets sitePerMinute.
     *
     * @return null|float
     */
    public function getSitePerMinute()
    {
        return $this->container['sitePerMinute'];
    }

    /**
     * Sets sitePerMinute.
     *
     * @param null|float $sitePerMinute sitePerMinute
     *
     * @return self
     */
    public function setSitePerMinute($sitePerMinute)
    {
        if (is_null($sitePerMinute)) {
            array_push($this->openAPINullablesSetToNull, 'sitePerMinute');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('sitePerMinute', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['sitePerMinute'] = $sitePerMinute;

        return $this;
    }

    /**
     * Gets siteParallel.
     *
     * @return null|float
     */
    public function getSiteParallel()
    {
        return $this->container['siteParallel'];
    }

    /**
     * Sets siteParallel.
     *
     * @param null|float $siteParallel siteParallel
     *
     * @return self
     */
    public function setSiteParallel($siteParallel)
    {
        if (is_null($siteParallel)) {
            array_push($this->openAPINullablesSetToNull, 'siteParallel');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('siteParallel', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['siteParallel'] = $siteParallel;

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
