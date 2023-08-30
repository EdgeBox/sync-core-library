<?php
/**
 * ProjectEntity.
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
 * ProjectEntity Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class ProjectEntity implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'ProjectEntity';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'type' => '\EdgeBox\SyncCore\V2\Raw\Model\SiteEnvironmentType',
        'status' => '\EdgeBox\SyncCore\V2\Raw\Model\ProjectStatus',
        'appType' => '\EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType',
        'customer' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity',
        'contract' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity',
        'name' => 'string',
        'uuid' => 'string',
        'id' => 'string',
        'createdAt' => 'float',
        'updatedAt' => 'float',
        'deletedAt' => 'float',
        'defaultMaxRequestsPerMinute' => 'float',
        'defaultMaxParallelRequests' => 'float',
        'maxRequestsPerMinute' => 'float',
        'maxParallelRequests' => 'float',
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
        'status' => null,
        'appType' => null,
        'customer' => null,
        'contract' => null,
        'name' => null,
        'uuid' => null,
        'id' => null,
        'createdAt' => null,
        'updatedAt' => null,
        'deletedAt' => null,
        'defaultMaxRequestsPerMinute' => null,
        'defaultMaxParallelRequests' => null,
        'maxRequestsPerMinute' => null,
        'maxParallelRequests' => null,
    ];

    /**
     * Array of nullable properties. Used for (de)serialization.
     *
     * @var bool[]
     */
    protected static array $openAPINullables = [
        'type' => false,
        'status' => false,
        'appType' => false,
        'customer' => false,
        'contract' => false,
        'name' => false,
        'uuid' => false,
        'id' => false,
        'createdAt' => false,
        'updatedAt' => false,
        'deletedAt' => true,
        'defaultMaxRequestsPerMinute' => true,
        'defaultMaxParallelRequests' => true,
        'maxRequestsPerMinute' => true,
        'maxParallelRequests' => true,
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
        'type' => 'type',
        'status' => 'status',
        'appType' => 'appType',
        'customer' => 'customer',
        'contract' => 'contract',
        'name' => 'name',
        'uuid' => 'uuid',
        'id' => 'id',
        'createdAt' => 'createdAt',
        'updatedAt' => 'updatedAt',
        'deletedAt' => 'deletedAt',
        'defaultMaxRequestsPerMinute' => 'defaultMaxRequestsPerMinute',
        'defaultMaxParallelRequests' => 'defaultMaxParallelRequests',
        'maxRequestsPerMinute' => 'maxRequestsPerMinute',
        'maxParallelRequests' => 'maxParallelRequests',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'type' => 'setType',
        'status' => 'setStatus',
        'appType' => 'setAppType',
        'customer' => 'setCustomer',
        'contract' => 'setContract',
        'name' => 'setName',
        'uuid' => 'setUuid',
        'id' => 'setId',
        'createdAt' => 'setCreatedAt',
        'updatedAt' => 'setUpdatedAt',
        'deletedAt' => 'setDeletedAt',
        'defaultMaxRequestsPerMinute' => 'setDefaultMaxRequestsPerMinute',
        'defaultMaxParallelRequests' => 'setDefaultMaxParallelRequests',
        'maxRequestsPerMinute' => 'setMaxRequestsPerMinute',
        'maxParallelRequests' => 'setMaxParallelRequests',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'type' => 'getType',
        'status' => 'getStatus',
        'appType' => 'getAppType',
        'customer' => 'getCustomer',
        'contract' => 'getContract',
        'name' => 'getName',
        'uuid' => 'getUuid',
        'id' => 'getId',
        'createdAt' => 'getCreatedAt',
        'updatedAt' => 'getUpdatedAt',
        'deletedAt' => 'getDeletedAt',
        'defaultMaxRequestsPerMinute' => 'getDefaultMaxRequestsPerMinute',
        'defaultMaxParallelRequests' => 'getDefaultMaxParallelRequests',
        'maxRequestsPerMinute' => 'getMaxRequestsPerMinute',
        'maxParallelRequests' => 'getMaxParallelRequests',
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
        $this->setIfExists('type', $data ?? [], null);
        $this->setIfExists('status', $data ?? [], null);
        $this->setIfExists('appType', $data ?? [], null);
        $this->setIfExists('customer', $data ?? [], null);
        $this->setIfExists('contract', $data ?? [], null);
        $this->setIfExists('name', $data ?? [], null);
        $this->setIfExists('uuid', $data ?? [], null);
        $this->setIfExists('id', $data ?? [], null);
        $this->setIfExists('createdAt', $data ?? [], null);
        $this->setIfExists('updatedAt', $data ?? [], null);
        $this->setIfExists('deletedAt', $data ?? [], null);
        $this->setIfExists('defaultMaxRequestsPerMinute', $data ?? [], null);
        $this->setIfExists('defaultMaxParallelRequests', $data ?? [], null);
        $this->setIfExists('maxRequestsPerMinute', $data ?? [], null);
        $this->setIfExists('maxParallelRequests', $data ?? [], null);
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

        if (null === $this->container['type']) {
            $invalidProperties[] = "'type' can't be null";
        }
        if (null === $this->container['customer']) {
            $invalidProperties[] = "'customer' can't be null";
        }
        if (null === $this->container['contract']) {
            $invalidProperties[] = "'contract' can't be null";
        }
        if (null === $this->container['name']) {
            $invalidProperties[] = "'name' can't be null";
        }
        if (null === $this->container['uuid']) {
            $invalidProperties[] = "'uuid' can't be null";
        }
        if (null === $this->container['id']) {
            $invalidProperties[] = "'id' can't be null";
        }
        if (null === $this->container['createdAt']) {
            $invalidProperties[] = "'createdAt' can't be null";
        }
        if (null === $this->container['updatedAt']) {
            $invalidProperties[] = "'updatedAt' can't be null";
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
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SiteEnvironmentType
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SiteEnvironmentType $type type
     *
     * @return self
     */
    public function setType($type)
    {
        if (is_null($type)) {
            throw new \InvalidArgumentException('non-nullable type cannot be null');
        }
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets status.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\ProjectStatus
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\ProjectStatus $status status
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
     * Gets appType.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType
     */
    public function getAppType()
    {
        return $this->container['appType'];
    }

    /**
     * Sets appType.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType $appType appType
     *
     * @return self
     */
    public function setAppType($appType)
    {
        if (is_null($appType)) {
            throw new \InvalidArgumentException('non-nullable appType cannot be null');
        }
        $this->container['appType'] = $appType;

        return $this;
    }

    /**
     * Gets customer.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity
     */
    public function getCustomer()
    {
        return $this->container['customer'];
    }

    /**
     * Sets customer.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity $customer customer
     *
     * @return self
     */
    public function setCustomer($customer)
    {
        if (is_null($customer)) {
            throw new \InvalidArgumentException('non-nullable customer cannot be null');
        }
        $this->container['customer'] = $customer;

        return $this;
    }

    /**
     * Gets contract.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity
     */
    public function getContract()
    {
        return $this->container['contract'];
    }

    /**
     * Sets contract.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity $contract contract
     *
     * @return self
     */
    public function setContract($contract)
    {
        if (is_null($contract)) {
            throw new \InvalidArgumentException('non-nullable contract cannot be null');
        }
        $this->container['contract'] = $contract;

        return $this;
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
     * Gets uuid.
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->container['uuid'];
    }

    /**
     * Sets uuid.
     *
     * @param string $uuid uuid
     *
     * @return self
     */
    public function setUuid($uuid)
    {
        if (is_null($uuid)) {
            throw new \InvalidArgumentException('non-nullable uuid cannot be null');
        }
        $this->container['uuid'] = $uuid;

        return $this;
    }

    /**
     * Gets id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id.
     *
     * @param string $id id
     *
     * @return self
     */
    public function setId($id)
    {
        if (is_null($id)) {
            throw new \InvalidArgumentException('non-nullable id cannot be null');
        }
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets createdAt.
     *
     * @return float
     */
    public function getCreatedAt()
    {
        return $this->container['createdAt'];
    }

    /**
     * Sets createdAt.
     *
     * @param float $createdAt createdAt
     *
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        if (is_null($createdAt)) {
            throw new \InvalidArgumentException('non-nullable createdAt cannot be null');
        }
        $this->container['createdAt'] = $createdAt;

        return $this;
    }

    /**
     * Gets updatedAt.
     *
     * @return float
     */
    public function getUpdatedAt()
    {
        return $this->container['updatedAt'];
    }

    /**
     * Sets updatedAt.
     *
     * @param float $updatedAt updatedAt
     *
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        if (is_null($updatedAt)) {
            throw new \InvalidArgumentException('non-nullable updatedAt cannot be null');
        }
        $this->container['updatedAt'] = $updatedAt;

        return $this;
    }

    /**
     * Gets deletedAt.
     *
     * @return null|float
     */
    public function getDeletedAt()
    {
        return $this->container['deletedAt'];
    }

    /**
     * Sets deletedAt.
     *
     * @param null|float $deletedAt deletedAt
     *
     * @return self
     */
    public function setDeletedAt($deletedAt)
    {
        if (is_null($deletedAt)) {
            array_push($this->openAPINullablesSetToNull, 'deletedAt');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('deletedAt', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['deletedAt'] = $deletedAt;

        return $this;
    }

    /**
     * Gets defaultMaxRequestsPerMinute.
     *
     * @return null|float
     */
    public function getDefaultMaxRequestsPerMinute()
    {
        return $this->container['defaultMaxRequestsPerMinute'];
    }

    /**
     * Sets defaultMaxRequestsPerMinute.
     *
     * @param null|float $defaultMaxRequestsPerMinute defaultMaxRequestsPerMinute
     *
     * @return self
     */
    public function setDefaultMaxRequestsPerMinute($defaultMaxRequestsPerMinute)
    {
        if (is_null($defaultMaxRequestsPerMinute)) {
            array_push($this->openAPINullablesSetToNull, 'defaultMaxRequestsPerMinute');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('defaultMaxRequestsPerMinute', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['defaultMaxRequestsPerMinute'] = $defaultMaxRequestsPerMinute;

        return $this;
    }

    /**
     * Gets defaultMaxParallelRequests.
     *
     * @return null|float
     */
    public function getDefaultMaxParallelRequests()
    {
        return $this->container['defaultMaxParallelRequests'];
    }

    /**
     * Sets defaultMaxParallelRequests.
     *
     * @param null|float $defaultMaxParallelRequests defaultMaxParallelRequests
     *
     * @return self
     */
    public function setDefaultMaxParallelRequests($defaultMaxParallelRequests)
    {
        if (is_null($defaultMaxParallelRequests)) {
            array_push($this->openAPINullablesSetToNull, 'defaultMaxParallelRequests');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('defaultMaxParallelRequests', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['defaultMaxParallelRequests'] = $defaultMaxParallelRequests;

        return $this;
    }

    /**
     * Gets maxRequestsPerMinute.
     *
     * @return null|float
     */
    public function getMaxRequestsPerMinute()
    {
        return $this->container['maxRequestsPerMinute'];
    }

    /**
     * Sets maxRequestsPerMinute.
     *
     * @param null|float $maxRequestsPerMinute maxRequestsPerMinute
     *
     * @return self
     */
    public function setMaxRequestsPerMinute($maxRequestsPerMinute)
    {
        if (is_null($maxRequestsPerMinute)) {
            array_push($this->openAPINullablesSetToNull, 'maxRequestsPerMinute');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('maxRequestsPerMinute', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['maxRequestsPerMinute'] = $maxRequestsPerMinute;

        return $this;
    }

    /**
     * Gets maxParallelRequests.
     *
     * @return null|float
     */
    public function getMaxParallelRequests()
    {
        return $this->container['maxParallelRequests'];
    }

    /**
     * Sets maxParallelRequests.
     *
     * @param null|float $maxParallelRequests maxParallelRequests
     *
     * @return self
     */
    public function setMaxParallelRequests($maxParallelRequests)
    {
        if (is_null($maxParallelRequests)) {
            array_push($this->openAPINullablesSetToNull, 'maxParallelRequests');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('maxParallelRequests', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['maxParallelRequests'] = $maxParallelRequests;

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
