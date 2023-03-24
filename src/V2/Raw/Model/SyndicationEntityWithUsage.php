<?php
/**
 * SyndicationEntityWithUsage.
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
 * SyndicationEntityWithUsage Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class SyndicationEntityWithUsage implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'SyndicationEntityWithUsage';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'rootEntityReference' => 'RemoteEntityReference',
        'rootEntityDetails' => 'RemoteEntityDetails',
        'status' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationStatus',
        'type' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationType',
        'rootEntity' => 'DynamicReference',
        'rootEntityType' => 'DynamicReference',
        'rootEntityTypeVersion' => 'DynamicReference',
        'targetSite' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity',
        'pools' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]',
        'flow' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity',
        'customer' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity',
        'project' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity',
        'operations' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationOperation[]',
        'migration' => 'DynamicReference',
        'migrationType' => '\EdgeBox\SyncCore\V2\Raw\Model\MigrationType',
        'finishedAt' => 'float',
        'dependsOnSyndication' => 'DynamicReference',
        'dryRun' => 'bool',
        'skipSyndication' => 'bool',
        'id' => 'string',
        'createdAt' => 'float',
        'updatedAt' => 'float',
        'deletedAt' => 'float',
        'usage' => 'RemoteEntityUsageEntity',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static $openAPIFormats = [
        'rootEntityReference' => null,
        'rootEntityDetails' => null,
        'status' => null,
        'type' => null,
        'rootEntity' => null,
        'rootEntityType' => null,
        'rootEntityTypeVersion' => null,
        'targetSite' => null,
        'pools' => null,
        'flow' => null,
        'customer' => null,
        'project' => null,
        'operations' => null,
        'migration' => null,
        'migrationType' => null,
        'finishedAt' => null,
        'dependsOnSyndication' => null,
        'dryRun' => null,
        'skipSyndication' => null,
        'id' => null,
        'createdAt' => null,
        'updatedAt' => null,
        'deletedAt' => null,
        'usage' => null,
    ];

    /**
     * Array of nullable properties. Used for (de)serialization.
     *
     * @var bool[]
     */
    protected static array $openAPINullables = [
        'rootEntityReference' => true,
        'rootEntityDetails' => true,
        'status' => false,
        'type' => false,
        'rootEntity' => true,
        'rootEntityType' => true,
        'rootEntityTypeVersion' => true,
        'targetSite' => false,
        'pools' => true,
        'flow' => false,
        'customer' => false,
        'project' => false,
        'operations' => true,
        'migration' => true,
        'migrationType' => false,
        'finishedAt' => true,
        'dependsOnSyndication' => true,
        'dryRun' => true,
        'skipSyndication' => true,
        'id' => false,
        'createdAt' => false,
        'updatedAt' => false,
        'deletedAt' => true,
        'usage' => true,
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
        'rootEntityReference' => 'rootEntityReference',
        'rootEntityDetails' => 'rootEntityDetails',
        'status' => 'status',
        'type' => 'type',
        'rootEntity' => 'rootEntity',
        'rootEntityType' => 'rootEntityType',
        'rootEntityTypeVersion' => 'rootEntityTypeVersion',
        'targetSite' => 'targetSite',
        'pools' => 'pools',
        'flow' => 'flow',
        'customer' => 'customer',
        'project' => 'project',
        'operations' => 'operations',
        'migration' => 'migration',
        'migrationType' => 'migrationType',
        'finishedAt' => 'finishedAt',
        'dependsOnSyndication' => 'dependsOnSyndication',
        'dryRun' => 'dryRun',
        'skipSyndication' => 'skipSyndication',
        'id' => 'id',
        'createdAt' => 'createdAt',
        'updatedAt' => 'updatedAt',
        'deletedAt' => 'deletedAt',
        'usage' => 'usage',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'rootEntityReference' => 'setRootEntityReference',
        'rootEntityDetails' => 'setRootEntityDetails',
        'status' => 'setStatus',
        'type' => 'setType',
        'rootEntity' => 'setRootEntity',
        'rootEntityType' => 'setRootEntityType',
        'rootEntityTypeVersion' => 'setRootEntityTypeVersion',
        'targetSite' => 'setTargetSite',
        'pools' => 'setPools',
        'flow' => 'setFlow',
        'customer' => 'setCustomer',
        'project' => 'setProject',
        'operations' => 'setOperations',
        'migration' => 'setMigration',
        'migrationType' => 'setMigrationType',
        'finishedAt' => 'setFinishedAt',
        'dependsOnSyndication' => 'setDependsOnSyndication',
        'dryRun' => 'setDryRun',
        'skipSyndication' => 'setSkipSyndication',
        'id' => 'setId',
        'createdAt' => 'setCreatedAt',
        'updatedAt' => 'setUpdatedAt',
        'deletedAt' => 'setDeletedAt',
        'usage' => 'setUsage',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'rootEntityReference' => 'getRootEntityReference',
        'rootEntityDetails' => 'getRootEntityDetails',
        'status' => 'getStatus',
        'type' => 'getType',
        'rootEntity' => 'getRootEntity',
        'rootEntityType' => 'getRootEntityType',
        'rootEntityTypeVersion' => 'getRootEntityTypeVersion',
        'targetSite' => 'getTargetSite',
        'pools' => 'getPools',
        'flow' => 'getFlow',
        'customer' => 'getCustomer',
        'project' => 'getProject',
        'operations' => 'getOperations',
        'migration' => 'getMigration',
        'migrationType' => 'getMigrationType',
        'finishedAt' => 'getFinishedAt',
        'dependsOnSyndication' => 'getDependsOnSyndication',
        'dryRun' => 'getDryRun',
        'skipSyndication' => 'getSkipSyndication',
        'id' => 'getId',
        'createdAt' => 'getCreatedAt',
        'updatedAt' => 'getUpdatedAt',
        'deletedAt' => 'getDeletedAt',
        'usage' => 'getUsage',
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
        $this->setIfExists('rootEntityReference', $data ?? [], null);
        $this->setIfExists('rootEntityDetails', $data ?? [], null);
        $this->setIfExists('status', $data ?? [], null);
        $this->setIfExists('type', $data ?? [], null);
        $this->setIfExists('rootEntity', $data ?? [], null);
        $this->setIfExists('rootEntityType', $data ?? [], null);
        $this->setIfExists('rootEntityTypeVersion', $data ?? [], null);
        $this->setIfExists('targetSite', $data ?? [], null);
        $this->setIfExists('pools', $data ?? [], null);
        $this->setIfExists('flow', $data ?? [], null);
        $this->setIfExists('customer', $data ?? [], null);
        $this->setIfExists('project', $data ?? [], null);
        $this->setIfExists('operations', $data ?? [], null);
        $this->setIfExists('migration', $data ?? [], null);
        $this->setIfExists('migrationType', $data ?? [], null);
        $this->setIfExists('finishedAt', $data ?? [], null);
        $this->setIfExists('dependsOnSyndication', $data ?? [], null);
        $this->setIfExists('dryRun', $data ?? [], null);
        $this->setIfExists('skipSyndication', $data ?? [], null);
        $this->setIfExists('id', $data ?? [], null);
        $this->setIfExists('createdAt', $data ?? [], null);
        $this->setIfExists('updatedAt', $data ?? [], null);
        $this->setIfExists('deletedAt', $data ?? [], null);
        $this->setIfExists('usage', $data ?? [], null);
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

        if (null === $this->container['status']) {
            $invalidProperties[] = "'status' can't be null";
        }
        if (null === $this->container['type']) {
            $invalidProperties[] = "'type' can't be null";
        }
        if (null === $this->container['targetSite']) {
            $invalidProperties[] = "'targetSite' can't be null";
        }
        if (null === $this->container['flow']) {
            $invalidProperties[] = "'flow' can't be null";
        }
        if (null === $this->container['customer']) {
            $invalidProperties[] = "'customer' can't be null";
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
     * Gets rootEntityReference.
     *
     * @return null|RemoteEntityReference
     */
    public function getRootEntityReference()
    {
        return $this->container['rootEntityReference'];
    }

    /**
     * Sets rootEntityReference.
     *
     * @param null|RemoteEntityReference $rootEntityReference rootEntityReference
     *
     * @return self
     */
    public function setRootEntityReference($rootEntityReference)
    {
        if (is_null($rootEntityReference)) {
            array_push($this->openAPINullablesSetToNull, 'rootEntityReference');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('rootEntityReference', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['rootEntityReference'] = $rootEntityReference;

        return $this;
    }

    /**
     * Gets rootEntityDetails.
     *
     * @return null|RemoteEntityDetails
     */
    public function getRootEntityDetails()
    {
        return $this->container['rootEntityDetails'];
    }

    /**
     * Sets rootEntityDetails.
     *
     * @param null|RemoteEntityDetails $rootEntityDetails rootEntityDetails
     *
     * @return self
     */
    public function setRootEntityDetails($rootEntityDetails)
    {
        if (is_null($rootEntityDetails)) {
            array_push($this->openAPINullablesSetToNull, 'rootEntityDetails');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('rootEntityDetails', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['rootEntityDetails'] = $rootEntityDetails;

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
        if (is_null($status)) {
            throw new \InvalidArgumentException('non-nullable status cannot be null');
        }
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets type.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SyndicationType
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SyndicationType $type type
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
     * Gets rootEntity.
     *
     * @return null|DynamicReference
     */
    public function getRootEntity()
    {
        return $this->container['rootEntity'];
    }

    /**
     * Sets rootEntity.
     *
     * @param null|DynamicReference $rootEntity rootEntity
     *
     * @return self
     */
    public function setRootEntity($rootEntity)
    {
        if (is_null($rootEntity)) {
            array_push($this->openAPINullablesSetToNull, 'rootEntity');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('rootEntity', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['rootEntity'] = $rootEntity;

        return $this;
    }

    /**
     * Gets rootEntityType.
     *
     * @return null|DynamicReference
     */
    public function getRootEntityType()
    {
        return $this->container['rootEntityType'];
    }

    /**
     * Sets rootEntityType.
     *
     * @param null|DynamicReference $rootEntityType rootEntityType
     *
     * @return self
     */
    public function setRootEntityType($rootEntityType)
    {
        if (is_null($rootEntityType)) {
            array_push($this->openAPINullablesSetToNull, 'rootEntityType');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('rootEntityType', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['rootEntityType'] = $rootEntityType;

        return $this;
    }

    /**
     * Gets rootEntityTypeVersion.
     *
     * @return null|DynamicReference
     */
    public function getRootEntityTypeVersion()
    {
        return $this->container['rootEntityTypeVersion'];
    }

    /**
     * Sets rootEntityTypeVersion.
     *
     * @param null|DynamicReference $rootEntityTypeVersion rootEntityTypeVersion
     *
     * @return self
     */
    public function setRootEntityTypeVersion($rootEntityTypeVersion)
    {
        if (is_null($rootEntityTypeVersion)) {
            array_push($this->openAPINullablesSetToNull, 'rootEntityTypeVersion');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('rootEntityTypeVersion', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['rootEntityTypeVersion'] = $rootEntityTypeVersion;

        return $this;
    }

    /**
     * Gets targetSite.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity
     */
    public function getTargetSite()
    {
        return $this->container['targetSite'];
    }

    /**
     * Sets targetSite.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity $targetSite targetSite
     *
     * @return self
     */
    public function setTargetSite($targetSite)
    {
        if (is_null($targetSite)) {
            throw new \InvalidArgumentException('non-nullable targetSite cannot be null');
        }
        $this->container['targetSite'] = $targetSite;

        return $this;
    }

    /**
     * Gets pools.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]
     */
    public function getPools()
    {
        return $this->container['pools'];
    }

    /**
     * Sets pools.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[] $pools pools
     *
     * @return self
     */
    public function setPools($pools)
    {
        if (is_null($pools)) {
            array_push($this->openAPINullablesSetToNull, 'pools');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('pools', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['pools'] = $pools;

        return $this;
    }

    /**
     * Gets flow.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity
     */
    public function getFlow()
    {
        return $this->container['flow'];
    }

    /**
     * Sets flow.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity $flow flow
     *
     * @return self
     */
    public function setFlow($flow)
    {
        if (is_null($flow)) {
            throw new \InvalidArgumentException('non-nullable flow cannot be null');
        }
        $this->container['flow'] = $flow;

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
     * Gets project.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity
     */
    public function getProject()
    {
        return $this->container['project'];
    }

    /**
     * Sets project.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity $project project
     *
     * @return self
     */
    public function setProject($project)
    {
        if (is_null($project)) {
            throw new \InvalidArgumentException('non-nullable project cannot be null');
        }
        $this->container['project'] = $project;

        return $this;
    }

    /**
     * Gets operations.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\SyndicationOperation[]
     */
    public function getOperations()
    {
        return $this->container['operations'];
    }

    /**
     * Sets operations.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\SyndicationOperation[] $operations operations
     *
     * @return self
     */
    public function setOperations($operations)
    {
        if (is_null($operations)) {
            array_push($this->openAPINullablesSetToNull, 'operations');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('operations', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['operations'] = $operations;

        return $this;
    }

    /**
     * Gets migration.
     *
     * @return null|DynamicReference
     */
    public function getMigration()
    {
        return $this->container['migration'];
    }

    /**
     * Sets migration.
     *
     * @param null|DynamicReference $migration migration
     *
     * @return self
     */
    public function setMigration($migration)
    {
        if (is_null($migration)) {
            array_push($this->openAPINullablesSetToNull, 'migration');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('migration', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['migration'] = $migration;

        return $this;
    }

    /**
     * Gets migrationType.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\MigrationType
     */
    public function getMigrationType()
    {
        return $this->container['migrationType'];
    }

    /**
     * Sets migrationType.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\MigrationType $migrationType migrationType
     *
     * @return self
     */
    public function setMigrationType($migrationType)
    {
        if (is_null($migrationType)) {
            throw new \InvalidArgumentException('non-nullable migrationType cannot be null');
        }
        $this->container['migrationType'] = $migrationType;

        return $this;
    }

    /**
     * Gets finishedAt.
     *
     * @return null|float
     */
    public function getFinishedAt()
    {
        return $this->container['finishedAt'];
    }

    /**
     * Sets finishedAt.
     *
     * @param null|float $finishedAt finishedAt
     *
     * @return self
     */
    public function setFinishedAt($finishedAt)
    {
        if (is_null($finishedAt)) {
            array_push($this->openAPINullablesSetToNull, 'finishedAt');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('finishedAt', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['finishedAt'] = $finishedAt;

        return $this;
    }

    /**
     * Gets dependsOnSyndication.
     *
     * @return null|DynamicReference
     */
    public function getDependsOnSyndication()
    {
        return $this->container['dependsOnSyndication'];
    }

    /**
     * Sets dependsOnSyndication.
     *
     * @param null|DynamicReference $dependsOnSyndication dependsOnSyndication
     *
     * @return self
     */
    public function setDependsOnSyndication($dependsOnSyndication)
    {
        if (is_null($dependsOnSyndication)) {
            array_push($this->openAPINullablesSetToNull, 'dependsOnSyndication');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('dependsOnSyndication', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['dependsOnSyndication'] = $dependsOnSyndication;

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
        if (is_null($dryRun)) {
            array_push($this->openAPINullablesSetToNull, 'dryRun');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('dryRun', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
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
        if (is_null($skipSyndication)) {
            array_push($this->openAPINullablesSetToNull, 'skipSyndication');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('skipSyndication', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['skipSyndication'] = $skipSyndication;

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
     * Gets usage.
     *
     * @return null|RemoteEntityUsageEntity
     */
    public function getUsage()
    {
        return $this->container['usage'];
    }

    /**
     * Sets usage.
     *
     * @param null|RemoteEntityUsageEntity $usage usage
     *
     * @return self
     */
    public function setUsage($usage)
    {
        if (is_null($usage)) {
            array_push($this->openAPINullablesSetToNull, 'usage');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('usage', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['usage'] = $usage;

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
