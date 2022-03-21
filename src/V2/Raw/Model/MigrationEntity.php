<?php
/**
 * MigrationEntity.
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
 * MigrationEntity Class Doc Comment.
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
class MigrationEntity implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'MigrationEntity';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'type' => '\EdgeBox\SyncCore\V2\Raw\Model\MigrationType',
        'entityTypeReference' => '\EdgeBox\SyncCore\V2\Raw\Model\EntityTypeVersionReference',
        'entityReferences' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntitySummary[]',
        'previousMigration' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'initialSetup' => 'bool',
        'changedAfter' => 'float',
        'dryRun' => 'bool',
        'skipSyndication' => 'bool',
        'flowMachineName' => 'string',
        'entityTypeVersion' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'entityType' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'entities' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]',
        'status' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationStatus',
        'customer' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'site' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'project' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'flow' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'id' => 'string',
        'createdAt' => 'float',
        'updatedAt' => 'float',
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
        'entityTypeReference' => null,
        'entityReferences' => null,
        'previousMigration' => null,
        'initialSetup' => null,
        'changedAfter' => null,
        'dryRun' => null,
        'skipSyndication' => null,
        'flowMachineName' => null,
        'entityTypeVersion' => null,
        'entityType' => null,
        'entities' => null,
        'status' => null,
        'customer' => null,
        'site' => null,
        'project' => null,
        'flow' => null,
        'id' => null,
        'createdAt' => null,
        'updatedAt' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'type' => 'type',
        'entityTypeReference' => 'entityTypeReference',
        'entityReferences' => 'entityReferences',
        'previousMigration' => 'previousMigration',
        'initialSetup' => 'initialSetup',
        'changedAfter' => 'changedAfter',
        'dryRun' => 'dryRun',
        'skipSyndication' => 'skipSyndication',
        'flowMachineName' => 'flowMachineName',
        'entityTypeVersion' => 'entityTypeVersion',
        'entityType' => 'entityType',
        'entities' => 'entities',
        'status' => 'status',
        'customer' => 'customer',
        'site' => 'site',
        'project' => 'project',
        'flow' => 'flow',
        'id' => 'id',
        'createdAt' => 'createdAt',
        'updatedAt' => 'updatedAt',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'type' => 'setType',
        'entityTypeReference' => 'setEntityTypeReference',
        'entityReferences' => 'setEntityReferences',
        'previousMigration' => 'setPreviousMigration',
        'initialSetup' => 'setInitialSetup',
        'changedAfter' => 'setChangedAfter',
        'dryRun' => 'setDryRun',
        'skipSyndication' => 'setSkipSyndication',
        'flowMachineName' => 'setFlowMachineName',
        'entityTypeVersion' => 'setEntityTypeVersion',
        'entityType' => 'setEntityType',
        'entities' => 'setEntities',
        'status' => 'setStatus',
        'customer' => 'setCustomer',
        'site' => 'setSite',
        'project' => 'setProject',
        'flow' => 'setFlow',
        'id' => 'setId',
        'createdAt' => 'setCreatedAt',
        'updatedAt' => 'setUpdatedAt',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'type' => 'getType',
        'entityTypeReference' => 'getEntityTypeReference',
        'entityReferences' => 'getEntityReferences',
        'previousMigration' => 'getPreviousMigration',
        'initialSetup' => 'getInitialSetup',
        'changedAfter' => 'getChangedAfter',
        'dryRun' => 'getDryRun',
        'skipSyndication' => 'getSkipSyndication',
        'flowMachineName' => 'getFlowMachineName',
        'entityTypeVersion' => 'getEntityTypeVersion',
        'entityType' => 'getEntityType',
        'entities' => 'getEntities',
        'status' => 'getStatus',
        'customer' => 'getCustomer',
        'site' => 'getSite',
        'project' => 'getProject',
        'flow' => 'getFlow',
        'id' => 'getId',
        'createdAt' => 'getCreatedAt',
        'updatedAt' => 'getUpdatedAt',
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
        $this->container['entityTypeReference'] = $data['entityTypeReference'] ?? null;
        $this->container['entityReferences'] = $data['entityReferences'] ?? null;
        $this->container['previousMigration'] = $data['previousMigration'] ?? null;
        $this->container['initialSetup'] = $data['initialSetup'] ?? null;
        $this->container['changedAfter'] = $data['changedAfter'] ?? null;
        $this->container['dryRun'] = $data['dryRun'] ?? null;
        $this->container['skipSyndication'] = $data['skipSyndication'] ?? null;
        $this->container['flowMachineName'] = $data['flowMachineName'] ?? null;
        $this->container['entityTypeVersion'] = $data['entityTypeVersion'] ?? null;
        $this->container['entityType'] = $data['entityType'] ?? null;
        $this->container['entities'] = $data['entities'] ?? null;
        $this->container['status'] = $data['status'] ?? null;
        $this->container['customer'] = $data['customer'] ?? null;
        $this->container['site'] = $data['site'] ?? null;
        $this->container['project'] = $data['project'] ?? null;
        $this->container['flow'] = $data['flow'] ?? null;
        $this->container['id'] = $data['id'] ?? null;
        $this->container['createdAt'] = $data['createdAt'] ?? null;
        $this->container['updatedAt'] = $data['updatedAt'] ?? null;
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
        if (null === $this->container['status']) {
            $invalidProperties[] = "'status' can't be null";
        }
        if (null === $this->container['customer']) {
            $invalidProperties[] = "'customer' can't be null";
        }
        if (null === $this->container['site']) {
            $invalidProperties[] = "'site' can't be null";
        }
        if (null === $this->container['project']) {
            $invalidProperties[] = "'project' can't be null";
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
     * @return \EdgeBox\SyncCore\V2\Raw\Model\MigrationType
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\MigrationType $type type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets entityTypeReference.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\EntityTypeVersionReference
     */
    public function getEntityTypeReference()
    {
        return $this->container['entityTypeReference'];
    }

    /**
     * Sets entityTypeReference.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\EntityTypeVersionReference $entityTypeReference entityTypeReference
     *
     * @return self
     */
    public function setEntityTypeReference($entityTypeReference)
    {
        $this->container['entityTypeReference'] = $entityTypeReference;

        return $this;
    }

    /**
     * Gets entityReferences.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntitySummary[]
     */
    public function getEntityReferences()
    {
        return $this->container['entityReferences'];
    }

    /**
     * Sets entityReferences.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntitySummary[] $entityReferences entityReferences
     *
     * @return self
     */
    public function setEntityReferences($entityReferences)
    {
        $this->container['entityReferences'] = $entityReferences;

        return $this;
    }

    /**
     * Gets previousMigration.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getPreviousMigration()
    {
        return $this->container['previousMigration'];
    }

    /**
     * Sets previousMigration.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $previousMigration previousMigration
     *
     * @return self
     */
    public function setPreviousMigration($previousMigration)
    {
        $this->container['previousMigration'] = $previousMigration;

        return $this;
    }

    /**
     * Gets initialSetup.
     *
     * @return null|bool
     */
    public function getInitialSetup()
    {
        return $this->container['initialSetup'];
    }

    /**
     * Sets initialSetup.
     *
     * @param null|bool $initialSetup initialSetup
     *
     * @return self
     */
    public function setInitialSetup($initialSetup)
    {
        $this->container['initialSetup'] = $initialSetup;

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
     * Gets dryRun.
     *
     * @return null|bool
     */
    public function getDryRun()
    {
        return $this->container['dryRun'];
    }

    /**
     * Sets dryRun.
     *
     * @param null|bool $dryRun dryRun
     *
     * @return self
     */
    public function setDryRun($dryRun)
    {
        $this->container['dryRun'] = $dryRun;

        return $this;
    }

    /**
     * Gets skipSyndication.
     *
     * @return null|bool
     */
    public function getSkipSyndication()
    {
        return $this->container['skipSyndication'];
    }

    /**
     * Sets skipSyndication.
     *
     * @param null|bool $skipSyndication skipSyndication
     *
     * @return self
     */
    public function setSkipSyndication($skipSyndication)
    {
        $this->container['skipSyndication'] = $skipSyndication;

        return $this;
    }

    /**
     * Gets flowMachineName.
     *
     * @return null|string
     */
    public function getFlowMachineName()
    {
        return $this->container['flowMachineName'];
    }

    /**
     * Sets flowMachineName.
     *
     * @param null|string $flowMachineName flowMachineName
     *
     * @return self
     */
    public function setFlowMachineName($flowMachineName)
    {
        $this->container['flowMachineName'] = $flowMachineName;

        return $this;
    }

    /**
     * Gets entityTypeVersion.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getEntityTypeVersion()
    {
        return $this->container['entityTypeVersion'];
    }

    /**
     * Sets entityTypeVersion.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $entityTypeVersion entityTypeVersion
     *
     * @return self
     */
    public function setEntityTypeVersion($entityTypeVersion)
    {
        $this->container['entityTypeVersion'] = $entityTypeVersion;

        return $this;
    }

    /**
     * Gets entityType.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getEntityType()
    {
        return $this->container['entityType'];
    }

    /**
     * Sets entityType.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $entityType entityType
     *
     * @return self
     */
    public function setEntityType($entityType)
    {
        $this->container['entityType'] = $entityType;

        return $this;
    }

    /**
     * Gets entities.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]
     */
    public function getEntities()
    {
        return $this->container['entities'];
    }

    /**
     * Sets entities.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[] $entities entities
     *
     * @return self
     */
    public function setEntities($entities)
    {
        $this->container['entities'] = $entities;

        return $this;
    }

    /**
     * Gets status.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SyndicationStatus
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status.
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
     * Gets customer.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getCustomer()
    {
        return $this->container['customer'];
    }

    /**
     * Sets customer.
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
     * Gets site.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getSite()
    {
        return $this->container['site'];
    }

    /**
     * Sets site.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $site site
     *
     * @return self
     */
    public function setSite($site)
    {
        $this->container['site'] = $site;

        return $this;
    }

    /**
     * Gets project.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getProject()
    {
        return $this->container['project'];
    }

    /**
     * Sets project.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $project project
     *
     * @return self
     */
    public function setProject($project)
    {
        $this->container['project'] = $project;

        return $this;
    }

    /**
     * Gets flow.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getFlow()
    {
        return $this->container['flow'];
    }

    /**
     * Sets flow.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $flow flow
     *
     * @return self
     */
    public function setFlow($flow)
    {
        $this->container['flow'] = $flow;

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
        $this->container['updatedAt'] = $updatedAt;

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
