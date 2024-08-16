<?php
/**
 * CreateFlowDto.
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
 * CreateFlowDto Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class CreateFlowDto implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'CreateFlowDto';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'name' => 'string',
        'machineName' => 'string',
        'containsPreviews' => 'bool',
        'allowedLanguages' => 'string[]',
        'sitePushesByMachineName' => '\EdgeBox\SyncCore\V2\Raw\Model\NewFlowSyndication[]',
        'sitePullsByMachineName' => '\EdgeBox\SyncCore\V2\Raw\Model\NewFlowSyndication[]',
        'remoteConfigFileId' => 'string',
        'status' => '\EdgeBox\SyncCore\V2\Raw\Model\FlowStatus',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static $openAPIFormats = [
        'name' => null,
        'machineName' => null,
        'containsPreviews' => null,
        'allowedLanguages' => null,
        'sitePushesByMachineName' => null,
        'sitePullsByMachineName' => null,
        'remoteConfigFileId' => null,
        'status' => null,
    ];

    /**
     * Array of nullable properties. Used for (de)serialization.
     *
     * @var bool[]
     */
    protected static array $openAPINullables = [
        'name' => false,
        'machineName' => false,
        'containsPreviews' => true,
        'allowedLanguages' => true,
        'sitePushesByMachineName' => false,
        'sitePullsByMachineName' => false,
        'remoteConfigFileId' => true,
        'status' => false,
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
        'name' => 'name',
        'machineName' => 'machineName',
        'containsPreviews' => 'containsPreviews',
        'allowedLanguages' => 'allowedLanguages',
        'sitePushesByMachineName' => 'sitePushesByMachineName',
        'sitePullsByMachineName' => 'sitePullsByMachineName',
        'remoteConfigFileId' => 'remoteConfigFileId',
        'status' => 'status',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'name' => 'setName',
        'machineName' => 'setMachineName',
        'containsPreviews' => 'setContainsPreviews',
        'allowedLanguages' => 'setAllowedLanguages',
        'sitePushesByMachineName' => 'setSitePushesByMachineName',
        'sitePullsByMachineName' => 'setSitePullsByMachineName',
        'remoteConfigFileId' => 'setRemoteConfigFileId',
        'status' => 'setStatus',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'name' => 'getName',
        'machineName' => 'getMachineName',
        'containsPreviews' => 'getContainsPreviews',
        'allowedLanguages' => 'getAllowedLanguages',
        'sitePushesByMachineName' => 'getSitePushesByMachineName',
        'sitePullsByMachineName' => 'getSitePullsByMachineName',
        'remoteConfigFileId' => 'getRemoteConfigFileId',
        'status' => 'getStatus',
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
        $this->setIfExists('name', $data ?? [], null);
        $this->setIfExists('machineName', $data ?? [], null);
        $this->setIfExists('containsPreviews', $data ?? [], null);
        $this->setIfExists('allowedLanguages', $data ?? [], null);
        $this->setIfExists('sitePushesByMachineName', $data ?? [], null);
        $this->setIfExists('sitePullsByMachineName', $data ?? [], null);
        $this->setIfExists('remoteConfigFileId', $data ?? [], null);
        $this->setIfExists('status', $data ?? [], null);
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

        if (null === $this->container['name']) {
            $invalidProperties[] = "'name' can't be null";
        }
        if (null === $this->container['machineName']) {
            $invalidProperties[] = "'machineName' can't be null";
        }
        if (null === $this->container['sitePushesByMachineName']) {
            $invalidProperties[] = "'sitePushesByMachineName' can't be null";
        }
        if (null === $this->container['sitePullsByMachineName']) {
            $invalidProperties[] = "'sitePullsByMachineName' can't be null";
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
     * Gets name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name.
     *
     * @param string $name name
     *
     * @return self
     */
    public function setName($name)
    {
        if (is_null($name)) {
            throw new \InvalidArgumentException('non-nullable name cannot be null');
        }
        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets machineName.
     *
     * @return string
     */
    public function getMachineName()
    {
        return $this->container['machineName'];
    }

    /**
     * Sets machineName.
     *
     * @param string $machineName machineName
     *
     * @return self
     */
    public function setMachineName($machineName)
    {
        if (is_null($machineName)) {
            throw new \InvalidArgumentException('non-nullable machineName cannot be null');
        }
        $this->container['machineName'] = $machineName;

        return $this;
    }

    /**
     * Gets containsPreviews.
     *
     * @return null|bool
     */
    public function getContainsPreviews()
    {
        return $this->container['containsPreviews'];
    }

    /**
     * Sets containsPreviews.
     *
     * @param null|bool $containsPreviews containsPreviews
     *
     * @return self
     */
    public function setContainsPreviews($containsPreviews)
    {
        if (is_null($containsPreviews)) {
            array_push($this->openAPINullablesSetToNull, 'containsPreviews');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('containsPreviews', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['containsPreviews'] = $containsPreviews;

        return $this;
    }

    /**
     * Gets allowedLanguages.
     *
     * @return null|string[]
     */
    public function getAllowedLanguages()
    {
        return $this->container['allowedLanguages'];
    }

    /**
     * Sets allowedLanguages.
     *
     * @param null|string[] $allowedLanguages allowedLanguages
     *
     * @return self
     */
    public function setAllowedLanguages($allowedLanguages)
    {
        if (is_null($allowedLanguages)) {
            array_push($this->openAPINullablesSetToNull, 'allowedLanguages');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('allowedLanguages', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['allowedLanguages'] = $allowedLanguages;

        return $this;
    }

    /**
     * Gets sitePushesByMachineName.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\NewFlowSyndication[]
     */
    public function getSitePushesByMachineName()
    {
        return $this->container['sitePushesByMachineName'];
    }

    /**
     * Sets sitePushesByMachineName.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\NewFlowSyndication[] $sitePushesByMachineName sitePushesByMachineName
     *
     * @return self
     */
    public function setSitePushesByMachineName($sitePushesByMachineName)
    {
        if (is_null($sitePushesByMachineName)) {
            throw new \InvalidArgumentException('non-nullable sitePushesByMachineName cannot be null');
        }
        $this->container['sitePushesByMachineName'] = $sitePushesByMachineName;

        return $this;
    }

    /**
     * Gets sitePullsByMachineName.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\NewFlowSyndication[]
     */
    public function getSitePullsByMachineName()
    {
        return $this->container['sitePullsByMachineName'];
    }

    /**
     * Sets sitePullsByMachineName.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\NewFlowSyndication[] $sitePullsByMachineName sitePullsByMachineName
     *
     * @return self
     */
    public function setSitePullsByMachineName($sitePullsByMachineName)
    {
        if (is_null($sitePullsByMachineName)) {
            throw new \InvalidArgumentException('non-nullable sitePullsByMachineName cannot be null');
        }
        $this->container['sitePullsByMachineName'] = $sitePullsByMachineName;

        return $this;
    }

    /**
     * Gets remoteConfigFileId.
     *
     * @return null|string
     */
    public function getRemoteConfigFileId()
    {
        return $this->container['remoteConfigFileId'];
    }

    /**
     * Sets remoteConfigFileId.
     *
     * @param null|string $remoteConfigFileId remoteConfigFileId
     *
     * @return self
     */
    public function setRemoteConfigFileId($remoteConfigFileId)
    {
        if (is_null($remoteConfigFileId)) {
            array_push($this->openAPINullablesSetToNull, 'remoteConfigFileId');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('remoteConfigFileId', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['remoteConfigFileId'] = $remoteConfigFileId;

        return $this;
    }

    /**
     * Gets status.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\FlowStatus
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\FlowStatus $status status
     *
     * @return self
     */
    public function setStatus($status)
    {
        if (is_null($status)) {
            throw new \InvalidArgumentException('non-nullable status cannot be null');
        }
        $this->container['status'] = $status;

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
