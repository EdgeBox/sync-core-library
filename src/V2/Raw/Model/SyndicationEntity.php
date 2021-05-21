<?php
/**
 * SyndicationEntity
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
 * SyndicationEntity Class Doc Comment
 *
 * @category Class
 * @package  EdgeBox\SyncCore\V2\Raw
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null  
 */
class SyndicationEntity implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'SyndicationEntity';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'status' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationStatus',
        'type' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationType',
        'rootEntity' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'targetSite' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'pools' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]',
        'flow' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'customer' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'project' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'operations' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationOperation[]',
        'migration' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'finishedAt' => 'float',
        'dependsOnSyndication' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'dryRun' => 'bool',
        'id' => 'string',
        'createdAt' => 'float',
        'updatedAt' => 'float'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'status' => null,
        'type' => null,
        'rootEntity' => null,
        'targetSite' => null,
        'pools' => null,
        'flow' => null,
        'customer' => null,
        'project' => null,
        'operations' => null,
        'migration' => null,
        'finishedAt' => null,
        'dependsOnSyndication' => null,
        'dryRun' => null,
        'id' => null,
        'createdAt' => null,
        'updatedAt' => null
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
        'status' => 'status',
        'type' => 'type',
        'rootEntity' => 'rootEntity',
        'targetSite' => 'targetSite',
        'pools' => 'pools',
        'flow' => 'flow',
        'customer' => 'customer',
        'project' => 'project',
        'operations' => 'operations',
        'migration' => 'migration',
        'finishedAt' => 'finishedAt',
        'dependsOnSyndication' => 'dependsOnSyndication',
        'dryRun' => 'dryRun',
        'id' => 'id',
        'createdAt' => 'createdAt',
        'updatedAt' => 'updatedAt'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'status' => 'setStatus',
        'type' => 'setType',
        'rootEntity' => 'setRootEntity',
        'targetSite' => 'setTargetSite',
        'pools' => 'setPools',
        'flow' => 'setFlow',
        'customer' => 'setCustomer',
        'project' => 'setProject',
        'operations' => 'setOperations',
        'migration' => 'setMigration',
        'finishedAt' => 'setFinishedAt',
        'dependsOnSyndication' => 'setDependsOnSyndication',
        'dryRun' => 'setDryRun',
        'id' => 'setId',
        'createdAt' => 'setCreatedAt',
        'updatedAt' => 'setUpdatedAt'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'status' => 'getStatus',
        'type' => 'getType',
        'rootEntity' => 'getRootEntity',
        'targetSite' => 'getTargetSite',
        'pools' => 'getPools',
        'flow' => 'getFlow',
        'customer' => 'getCustomer',
        'project' => 'getProject',
        'operations' => 'getOperations',
        'migration' => 'getMigration',
        'finishedAt' => 'getFinishedAt',
        'dependsOnSyndication' => 'getDependsOnSyndication',
        'dryRun' => 'getDryRun',
        'id' => 'getId',
        'createdAt' => 'getCreatedAt',
        'updatedAt' => 'getUpdatedAt'
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
        $this->container['status'] = $data['status'] ?? null;
        $this->container['type'] = $data['type'] ?? null;
        $this->container['rootEntity'] = $data['rootEntity'] ?? null;
        $this->container['targetSite'] = $data['targetSite'] ?? null;
        $this->container['pools'] = $data['pools'] ?? null;
        $this->container['flow'] = $data['flow'] ?? null;
        $this->container['customer'] = $data['customer'] ?? null;
        $this->container['project'] = $data['project'] ?? null;
        $this->container['operations'] = $data['operations'] ?? null;
        $this->container['migration'] = $data['migration'] ?? null;
        $this->container['finishedAt'] = $data['finishedAt'] ?? null;
        $this->container['dependsOnSyndication'] = $data['dependsOnSyndication'] ?? null;
        $this->container['dryRun'] = $data['dryRun'] ?? null;
        $this->container['id'] = $data['id'] ?? null;
        $this->container['createdAt'] = $data['createdAt'] ?? null;
        $this->container['updatedAt'] = $data['updatedAt'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['status'] === null) {
            $invalidProperties[] = "'status' can't be null";
        }
        if ($this->container['type'] === null) {
            $invalidProperties[] = "'type' can't be null";
        }
        if ($this->container['rootEntity'] === null) {
            $invalidProperties[] = "'rootEntity' can't be null";
        }
        if ($this->container['targetSite'] === null) {
            $invalidProperties[] = "'targetSite' can't be null";
        }
        if ($this->container['pools'] === null) {
            $invalidProperties[] = "'pools' can't be null";
        }
        if ($this->container['flow'] === null) {
            $invalidProperties[] = "'flow' can't be null";
        }
        if ($this->container['customer'] === null) {
            $invalidProperties[] = "'customer' can't be null";
        }
        if ($this->container['id'] === null) {
            $invalidProperties[] = "'id' can't be null";
        }
        if ($this->container['createdAt'] === null) {
            $invalidProperties[] = "'createdAt' can't be null";
        }
        if ($this->container['updatedAt'] === null) {
            $invalidProperties[] = "'updatedAt' can't be null";
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
     * Gets status
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SyndicationStatus
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SyndicationStatus $status status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets type
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SyndicationType
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SyndicationType $type type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets rootEntity
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getRootEntity()
    {
        return $this->container['rootEntity'];
    }

    /**
     * Sets rootEntity
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $rootEntity rootEntity
     *
     * @return self
     */
    public function setRootEntity($rootEntity)
    {
        $this->container['rootEntity'] = $rootEntity;

        return $this;
    }

    /**
     * Gets targetSite
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getTargetSite()
    {
        return $this->container['targetSite'];
    }

    /**
     * Sets targetSite
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $targetSite targetSite
     *
     * @return self
     */
    public function setTargetSite($targetSite)
    {
        $this->container['targetSite'] = $targetSite;

        return $this;
    }

    /**
     * Gets pools
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]
     */
    public function getPools()
    {
        return $this->container['pools'];
    }

    /**
     * Sets pools
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[] $pools pools
     *
     * @return self
     */
    public function setPools($pools)
    {
        $this->container['pools'] = $pools;

        return $this;
    }

    /**
     * Gets flow
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getFlow()
    {
        return $this->container['flow'];
    }

    /**
     * Sets flow
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $flow flow
     *
     * @return self
     */
    public function setFlow($flow)
    {
        $this->container['flow'] = $flow;

        return $this;
    }

    /**
     * Gets customer
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getCustomer()
    {
        return $this->container['customer'];
    }

    /**
     * Sets customer
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $customer customer
     *
     * @return self
     */
    public function setCustomer($customer)
    {
        $this->container['customer'] = $customer;

        return $this;
    }

    /**
     * Gets project
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference|null
     */
    public function getProject()
    {
        return $this->container['project'];
    }

    /**
     * Sets project
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference|null $project project
     *
     * @return self
     */
    public function setProject($project)
    {
        $this->container['project'] = $project;

        return $this;
    }

    /**
     * Gets operations
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SyndicationOperation[]|null
     */
    public function getOperations()
    {
        return $this->container['operations'];
    }

    /**
     * Sets operations
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SyndicationOperation[]|null $operations operations
     *
     * @return self
     */
    public function setOperations($operations)
    {
        $this->container['operations'] = $operations;

        return $this;
    }

    /**
     * Gets migration
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference|null
     */
    public function getMigration()
    {
        return $this->container['migration'];
    }

    /**
     * Sets migration
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference|null $migration migration
     *
     * @return self
     */
    public function setMigration($migration)
    {
        $this->container['migration'] = $migration;

        return $this;
    }

    /**
     * Gets finishedAt
     *
     * @return float|null
     */
    public function getFinishedAt()
    {
        return $this->container['finishedAt'];
    }

    /**
     * Sets finishedAt
     *
     * @param float|null $finishedAt finishedAt
     *
     * @return self
     */
    public function setFinishedAt($finishedAt)
    {
        $this->container['finishedAt'] = $finishedAt;

        return $this;
    }

    /**
     * Gets dependsOnSyndication
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference|null
     */
    public function getDependsOnSyndication()
    {
        return $this->container['dependsOnSyndication'];
    }

    /**
     * Sets dependsOnSyndication
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference|null $dependsOnSyndication dependsOnSyndication
     *
     * @return self
     */
    public function setDependsOnSyndication($dependsOnSyndication)
    {
        $this->container['dependsOnSyndication'] = $dependsOnSyndication;

        return $this;
    }

    /**
     * Gets dryRun
     *
     * @return bool|null
     */
    public function getDryRun()
    {
        return $this->container['dryRun'];
    }

    /**
     * Sets dryRun
     *
     * @param bool|null $dryRun dryRun
     *
     * @return self
     */
    public function setDryRun($dryRun)
    {
        $this->container['dryRun'] = $dryRun;

        return $this;
    }

    /**
     * Gets id
     *
     * @return string
     */
    public function getId()
    {
        return $this->container['id'];
    }

    /**
     * Sets id
     *
     * @param string $id id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->container['id'] = $id;

        return $this;
    }

    /**
     * Gets createdAt
     *
     * @return float
     */
    public function getCreatedAt()
    {
        return $this->container['createdAt'];
    }

    /**
     * Sets createdAt
     *
     * @param float $createdAt createdAt
     *
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->container['createdAt'] = $createdAt;

        return $this;
    }

    /**
     * Gets updatedAt
     *
     * @return float
     */
    public function getUpdatedAt()
    {
        return $this->container['updatedAt'];
    }

    /**
     * Sets updatedAt
     *
     * @param float $updatedAt updatedAt
     *
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->container['updatedAt'] = $updatedAt;

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


