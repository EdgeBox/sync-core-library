<?php
/**
 * SyndicationError.
 *
 * PHP version 7.3
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
 * OpenAPI Generator version: 5.3.0
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
 * SyndicationError Class Doc Comment.
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
class SyndicationError implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'SyndicationError';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'type' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationErrorType',
        'timestamp' => 'float',
        'errorMessage' => 'string',
        'callStack' => 'string',
        'statusCode' => 'float',
        'requestDetails' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static $openAPIFormats = [
        'type' => null,
        'timestamp' => null,
        'errorMessage' => null,
        'callStack' => null,
        'statusCode' => null,
        'requestDetails' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'type' => 'type',
        'timestamp' => 'timestamp',
        'errorMessage' => 'errorMessage',
        'callStack' => 'callStack',
        'statusCode' => 'statusCode',
        'requestDetails' => 'requestDetails',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'type' => 'setType',
        'timestamp' => 'setTimestamp',
        'errorMessage' => 'setErrorMessage',
        'callStack' => 'setCallStack',
        'statusCode' => 'setStatusCode',
        'requestDetails' => 'setRequestDetails',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'type' => 'getType',
        'timestamp' => 'getTimestamp',
        'errorMessage' => 'getErrorMessage',
        'callStack' => 'getCallStack',
        'statusCode' => 'getStatusCode',
        'requestDetails' => 'getRequestDetails',
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
        $this->container['type'] = $data['type'] ?? null;
        $this->container['timestamp'] = $data['timestamp'] ?? null;
        $this->container['errorMessage'] = $data['errorMessage'] ?? null;
        $this->container['callStack'] = $data['callStack'] ?? null;
        $this->container['statusCode'] = $data['statusCode'] ?? null;
        $this->container['requestDetails'] = $data['requestDetails'] ?? null;
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

        if (null === $this->container['type']) {
            $invalidProperties[] = "'type' can't be null";
        }
        if (null === $this->container['timestamp']) {
            $invalidProperties[] = "'timestamp' can't be null";
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
     * Gets type.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SyndicationErrorType
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SyndicationErrorType $type type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets timestamp.
     *
     * @return float
     */
    public function getTimestamp()
    {
        return $this->container['timestamp'];
    }

    /**
     * Sets timestamp.
     *
     * @param float $timestamp timestamp
     *
     * @return self
     */
    public function setTimestamp($timestamp)
    {
        $this->container['timestamp'] = $timestamp;

        return $this;
    }

    /**
     * Gets errorMessage.
     *
     * @return null|string
     */
    public function getErrorMessage()
    {
        return $this->container['errorMessage'];
    }

    /**
     * Sets errorMessage.
     *
     * @param null|string $errorMessage errorMessage
     *
     * @return self
     */
    public function setErrorMessage($errorMessage)
    {
        $this->container['errorMessage'] = $errorMessage;

        return $this;
    }

    /**
     * Gets callStack.
     *
     * @return null|string
     */
    public function getCallStack()
    {
        return $this->container['callStack'];
    }

    /**
     * Sets callStack.
     *
     * @param null|string $callStack callStack
     *
     * @return self
     */
    public function setCallStack($callStack)
    {
        $this->container['callStack'] = $callStack;

        return $this;
    }

    /**
     * Gets statusCode.
     *
     * @return null|float
     */
    public function getStatusCode()
    {
        return $this->container['statusCode'];
    }

    /**
     * Sets statusCode.
     *
     * @param null|float $statusCode statusCode
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->container['statusCode'] = $statusCode;

        return $this;
    }

    /**
     * Gets requestDetails.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getRequestDetails()
    {
        return $this->container['requestDetails'];
    }

    /**
     * Sets requestDetails.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $requestDetails requestDetails
     *
     * @return self
     */
    public function setRequestDetails($requestDetails)
    {
        $this->container['requestDetails'] = $requestDetails;

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
     * of any type other than a resource
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
