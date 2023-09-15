<?php
/**
 * RemoteEntityRevisionEntity.
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
 * RemoteEntityRevisionEntity Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class RemoteEntityRevisionEntity implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'RemoteEntityRevisionEntity';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'name' => 'string',
        'remoteUuid' => 'string',
        'remoteUniqueId' => 'string',
        'language' => 'string',
        'directDependencies' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency[]',
        'appType' => '\EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType',
        'poolMachineNames' => 'string[]',
        'isTranslationRoot' => 'bool',
        'viewUrl' => 'string',
        'deleted' => 'bool',
        'embed' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityRootEmbed[]',
        'properties' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityProperty[]',
        'allDependencies' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependencies[]',
        'entityType' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity',
        'entityTypeVersion' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity',
        'pools' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]',
        'customer' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity',
        'project' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity',
        'translationRoot' => 'DynamicReference',
        'previewHtml' => 'DynamicReference',
        'id' => 'string',
        'createdAt' => 'float',
        'updatedAt' => 'float',
        'deletedAt' => 'float',
        'clonedFrom' => 'DynamicReference',
        'versionId' => 'string',
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
        'remoteUuid' => null,
        'remoteUniqueId' => null,
        'language' => null,
        'directDependencies' => null,
        'appType' => null,
        'poolMachineNames' => null,
        'isTranslationRoot' => null,
        'viewUrl' => null,
        'deleted' => null,
        'embed' => null,
        'properties' => null,
        'allDependencies' => null,
        'entityType' => null,
        'entityTypeVersion' => null,
        'pools' => null,
        'customer' => null,
        'project' => null,
        'translationRoot' => null,
        'previewHtml' => null,
        'id' => null,
        'createdAt' => null,
        'updatedAt' => null,
        'deletedAt' => null,
        'clonedFrom' => null,
        'versionId' => null,
    ];

    /**
     * Array of nullable properties. Used for (de)serialization.
     *
     * @var bool[]
     */
    protected static array $openAPINullables = [
        'name' => true,
        'remoteUuid' => true,
        'remoteUniqueId' => true,
        'language' => false,
        'directDependencies' => true,
        'appType' => false,
        'poolMachineNames' => false,
        'isTranslationRoot' => true,
        'viewUrl' => false,
        'deleted' => true,
        'embed' => true,
        'properties' => false,
        'allDependencies' => true,
        'entityType' => false,
        'entityTypeVersion' => false,
        'pools' => false,
        'customer' => false,
        'project' => false,
        'translationRoot' => true,
        'previewHtml' => true,
        'id' => false,
        'createdAt' => false,
        'updatedAt' => false,
        'deletedAt' => true,
        'clonedFrom' => true,
        'versionId' => false,
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
        'remoteUuid' => 'remoteUuid',
        'remoteUniqueId' => 'remoteUniqueId',
        'language' => 'language',
        'directDependencies' => 'directDependencies',
        'appType' => 'appType',
        'poolMachineNames' => 'poolMachineNames',
        'isTranslationRoot' => 'isTranslationRoot',
        'viewUrl' => 'viewUrl',
        'deleted' => 'deleted',
        'embed' => 'embed',
        'properties' => 'properties',
        'allDependencies' => 'allDependencies',
        'entityType' => 'entityType',
        'entityTypeVersion' => 'entityTypeVersion',
        'pools' => 'pools',
        'customer' => 'customer',
        'project' => 'project',
        'translationRoot' => 'translationRoot',
        'previewHtml' => 'previewHtml',
        'id' => 'id',
        'createdAt' => 'createdAt',
        'updatedAt' => 'updatedAt',
        'deletedAt' => 'deletedAt',
        'clonedFrom' => 'clonedFrom',
        'versionId' => 'versionId',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'name' => 'setName',
        'remoteUuid' => 'setRemoteUuid',
        'remoteUniqueId' => 'setRemoteUniqueId',
        'language' => 'setLanguage',
        'directDependencies' => 'setDirectDependencies',
        'appType' => 'setAppType',
        'poolMachineNames' => 'setPoolMachineNames',
        'isTranslationRoot' => 'setIsTranslationRoot',
        'viewUrl' => 'setViewUrl',
        'deleted' => 'setDeleted',
        'embed' => 'setEmbed',
        'properties' => 'setProperties',
        'allDependencies' => 'setAllDependencies',
        'entityType' => 'setEntityType',
        'entityTypeVersion' => 'setEntityTypeVersion',
        'pools' => 'setPools',
        'customer' => 'setCustomer',
        'project' => 'setProject',
        'translationRoot' => 'setTranslationRoot',
        'previewHtml' => 'setPreviewHtml',
        'id' => 'setId',
        'createdAt' => 'setCreatedAt',
        'updatedAt' => 'setUpdatedAt',
        'deletedAt' => 'setDeletedAt',
        'clonedFrom' => 'setClonedFrom',
        'versionId' => 'setVersionId',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'name' => 'getName',
        'remoteUuid' => 'getRemoteUuid',
        'remoteUniqueId' => 'getRemoteUniqueId',
        'language' => 'getLanguage',
        'directDependencies' => 'getDirectDependencies',
        'appType' => 'getAppType',
        'poolMachineNames' => 'getPoolMachineNames',
        'isTranslationRoot' => 'getIsTranslationRoot',
        'viewUrl' => 'getViewUrl',
        'deleted' => 'getDeleted',
        'embed' => 'getEmbed',
        'properties' => 'getProperties',
        'allDependencies' => 'getAllDependencies',
        'entityType' => 'getEntityType',
        'entityTypeVersion' => 'getEntityTypeVersion',
        'pools' => 'getPools',
        'customer' => 'getCustomer',
        'project' => 'getProject',
        'translationRoot' => 'getTranslationRoot',
        'previewHtml' => 'getPreviewHtml',
        'id' => 'getId',
        'createdAt' => 'getCreatedAt',
        'updatedAt' => 'getUpdatedAt',
        'deletedAt' => 'getDeletedAt',
        'clonedFrom' => 'getClonedFrom',
        'versionId' => 'getVersionId',
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
        $this->setIfExists('remoteUuid', $data ?? [], null);
        $this->setIfExists('remoteUniqueId', $data ?? [], null);
        $this->setIfExists('language', $data ?? [], null);
        $this->setIfExists('directDependencies', $data ?? [], null);
        $this->setIfExists('appType', $data ?? [], null);
        $this->setIfExists('poolMachineNames', $data ?? [], null);
        $this->setIfExists('isTranslationRoot', $data ?? [], null);
        $this->setIfExists('viewUrl', $data ?? [], null);
        $this->setIfExists('deleted', $data ?? [], null);
        $this->setIfExists('embed', $data ?? [], null);
        $this->setIfExists('properties', $data ?? [], null);
        $this->setIfExists('allDependencies', $data ?? [], null);
        $this->setIfExists('entityType', $data ?? [], null);
        $this->setIfExists('entityTypeVersion', $data ?? [], null);
        $this->setIfExists('pools', $data ?? [], null);
        $this->setIfExists('customer', $data ?? [], null);
        $this->setIfExists('project', $data ?? [], null);
        $this->setIfExists('translationRoot', $data ?? [], null);
        $this->setIfExists('previewHtml', $data ?? [], null);
        $this->setIfExists('id', $data ?? [], null);
        $this->setIfExists('createdAt', $data ?? [], null);
        $this->setIfExists('updatedAt', $data ?? [], null);
        $this->setIfExists('deletedAt', $data ?? [], null);
        $this->setIfExists('clonedFrom', $data ?? [], null);
        $this->setIfExists('versionId', $data ?? [], null);
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

        if (null === $this->container['language']) {
            $invalidProperties[] = "'language' can't be null";
        }
        if (null === $this->container['appType']) {
            $invalidProperties[] = "'appType' can't be null";
        }
        if (null === $this->container['poolMachineNames']) {
            $invalidProperties[] = "'poolMachineNames' can't be null";
        }
        if (null === $this->container['viewUrl']) {
            $invalidProperties[] = "'viewUrl' can't be null";
        }
        if (null === $this->container['properties']) {
            $invalidProperties[] = "'properties' can't be null";
        }
        if (null === $this->container['entityType']) {
            $invalidProperties[] = "'entityType' can't be null";
        }
        if (null === $this->container['entityTypeVersion']) {
            $invalidProperties[] = "'entityTypeVersion' can't be null";
        }
        if (null === $this->container['pools']) {
            $invalidProperties[] = "'pools' can't be null";
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
        if (null === $this->container['versionId']) {
            $invalidProperties[] = "'versionId' can't be null";
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
     * @return null|string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name.
     *
     * @param null|string $name name
     *
     * @return self
     */
    public function setName($name)
    {
        if (is_null($name)) {
            array_push($this->openAPINullablesSetToNull, 'name');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('name', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets remoteUuid.
     *
     * @return null|string
     */
    public function getRemoteUuid()
    {
        return $this->container['remoteUuid'];
    }

    /**
     * Sets remoteUuid.
     *
     * @param null|string $remoteUuid remoteUuid
     *
     * @return self
     */
    public function setRemoteUuid($remoteUuid)
    {
        if (is_null($remoteUuid)) {
            array_push($this->openAPINullablesSetToNull, 'remoteUuid');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('remoteUuid', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['remoteUuid'] = $remoteUuid;

        return $this;
    }

    /**
     * Gets remoteUniqueId.
     *
     * @return null|string
     */
    public function getRemoteUniqueId()
    {
        return $this->container['remoteUniqueId'];
    }

    /**
     * Sets remoteUniqueId.
     *
     * @param null|string $remoteUniqueId remoteUniqueId
     *
     * @return self
     */
    public function setRemoteUniqueId($remoteUniqueId)
    {
        if (is_null($remoteUniqueId)) {
            array_push($this->openAPINullablesSetToNull, 'remoteUniqueId');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('remoteUniqueId', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['remoteUniqueId'] = $remoteUniqueId;

        return $this;
    }

    /**
     * Gets language.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->container['language'];
    }

    /**
     * Sets language.
     *
     * @param string $language language
     *
     * @return self
     */
    public function setLanguage($language)
    {
        if (is_null($language)) {
            throw new \InvalidArgumentException('non-nullable language cannot be null');
        }
        $this->container['language'] = $language;

        return $this;
    }

    /**
     * Gets directDependencies.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency[]
     */
    public function getDirectDependencies()
    {
        return $this->container['directDependencies'];
    }

    /**
     * Sets directDependencies.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency[] $directDependencies directDependencies
     *
     * @return self
     */
    public function setDirectDependencies($directDependencies)
    {
        if (is_null($directDependencies)) {
            array_push($this->openAPINullablesSetToNull, 'directDependencies');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('directDependencies', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['directDependencies'] = $directDependencies;

        return $this;
    }

    /**
     * Gets appType.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType
     */
    public function getAppType()
    {
        return $this->container['appType'];
    }

    /**
     * Sets appType.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType $appType appType
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
     * Gets poolMachineNames.
     *
     * @return string[]
     */
    public function getPoolMachineNames()
    {
        return $this->container['poolMachineNames'];
    }

    /**
     * Sets poolMachineNames.
     *
     * @param string[] $poolMachineNames poolMachineNames
     *
     * @return self
     */
    public function setPoolMachineNames($poolMachineNames)
    {
        if (is_null($poolMachineNames)) {
            throw new \InvalidArgumentException('non-nullable poolMachineNames cannot be null');
        }
        $this->container['poolMachineNames'] = $poolMachineNames;

        return $this;
    }

    /**
     * Gets isTranslationRoot.
     *
     * @return null|bool
     */
    public function getIsTranslationRoot()
    {
        return $this->container['isTranslationRoot'];
    }

    /**
     * Sets isTranslationRoot.
     *
     * @param null|bool $isTranslationRoot isTranslationRoot
     *
     * @return self
     */
    public function setIsTranslationRoot($isTranslationRoot)
    {
        if (is_null($isTranslationRoot)) {
            array_push($this->openAPINullablesSetToNull, 'isTranslationRoot');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('isTranslationRoot', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['isTranslationRoot'] = $isTranslationRoot;

        return $this;
    }

    /**
     * Gets viewUrl.
     *
     * @return string
     */
    public function getViewUrl()
    {
        return $this->container['viewUrl'];
    }

    /**
     * Sets viewUrl.
     *
     * @param string $viewUrl viewUrl
     *
     * @return self
     */
    public function setViewUrl($viewUrl)
    {
        if (is_null($viewUrl)) {
            throw new \InvalidArgumentException('non-nullable viewUrl cannot be null');
        }
        $this->container['viewUrl'] = $viewUrl;

        return $this;
    }

    /**
     * Gets deleted.
     *
     * @return null|bool
     */
    public function getDeleted()
    {
        return $this->container['deleted'];
    }

    /**
     * Sets deleted.
     *
     * @param null|bool $deleted deleted
     *
     * @return self
     */
    public function setDeleted($deleted)
    {
        if (is_null($deleted)) {
            array_push($this->openAPINullablesSetToNull, 'deleted');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('deleted', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['deleted'] = $deleted;

        return $this;
    }

    /**
     * Gets embed.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityRootEmbed[]
     */
    public function getEmbed()
    {
        return $this->container['embed'];
    }

    /**
     * Sets embed.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityRootEmbed[] $embed embed
     *
     * @return self
     */
    public function setEmbed($embed)
    {
        if (is_null($embed)) {
            array_push($this->openAPINullablesSetToNull, 'embed');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('embed', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['embed'] = $embed;

        return $this;
    }

    /**
     * Gets properties.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityProperty[]
     */
    public function getProperties()
    {
        return $this->container['properties'];
    }

    /**
     * Sets properties.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityProperty[] $properties properties
     *
     * @return self
     */
    public function setProperties($properties)
    {
        if (is_null($properties)) {
            throw new \InvalidArgumentException('non-nullable properties cannot be null');
        }
        $this->container['properties'] = $properties;

        return $this;
    }

    /**
     * Gets allDependencies.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependencies[]
     */
    public function getAllDependencies()
    {
        return $this->container['allDependencies'];
    }

    /**
     * Sets allDependencies.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependencies[] $allDependencies allDependencies
     *
     * @return self
     */
    public function setAllDependencies($allDependencies)
    {
        if (is_null($allDependencies)) {
            array_push($this->openAPINullablesSetToNull, 'allDependencies');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('allDependencies', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['allDependencies'] = $allDependencies;

        return $this;
    }

    /**
     * Gets entityType.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity
     */
    public function getEntityType()
    {
        return $this->container['entityType'];
    }

    /**
     * Sets entityType.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity $entityType entityType
     *
     * @return self
     */
    public function setEntityType($entityType)
    {
        if (is_null($entityType)) {
            throw new \InvalidArgumentException('non-nullable entityType cannot be null');
        }
        $this->container['entityType'] = $entityType;

        return $this;
    }

    /**
     * Gets entityTypeVersion.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity
     */
    public function getEntityTypeVersion()
    {
        return $this->container['entityTypeVersion'];
    }

    /**
     * Sets entityTypeVersion.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependenciesEntity $entityTypeVersion entityTypeVersion
     *
     * @return self
     */
    public function setEntityTypeVersion($entityTypeVersion)
    {
        if (is_null($entityTypeVersion)) {
            throw new \InvalidArgumentException('non-nullable entityTypeVersion cannot be null');
        }
        $this->container['entityTypeVersion'] = $entityTypeVersion;

        return $this;
    }

    /**
     * Gets pools.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]
     */
    public function getPools()
    {
        return $this->container['pools'];
    }

    /**
     * Sets pools.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[] $pools pools
     *
     * @return self
     */
    public function setPools($pools)
    {
        if (is_null($pools)) {
            throw new \InvalidArgumentException('non-nullable pools cannot be null');
        }
        $this->container['pools'] = $pools;

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
     * Gets translationRoot.
     *
     * @return null|DynamicReference
     */
    public function getTranslationRoot()
    {
        return $this->container['translationRoot'];
    }

    /**
     * Sets translationRoot.
     *
     * @param null|DynamicReference $translationRoot translationRoot
     *
     * @return self
     */
    public function setTranslationRoot($translationRoot)
    {
        if (is_null($translationRoot)) {
            array_push($this->openAPINullablesSetToNull, 'translationRoot');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('translationRoot', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['translationRoot'] = $translationRoot;

        return $this;
    }

    /**
     * Gets previewHtml.
     *
     * @return null|DynamicReference
     */
    public function getPreviewHtml()
    {
        return $this->container['previewHtml'];
    }

    /**
     * Sets previewHtml.
     *
     * @param null|DynamicReference $previewHtml previewHtml
     *
     * @return self
     */
    public function setPreviewHtml($previewHtml)
    {
        if (is_null($previewHtml)) {
            array_push($this->openAPINullablesSetToNull, 'previewHtml');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('previewHtml', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['previewHtml'] = $previewHtml;

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
     * Gets clonedFrom.
     *
     * @return null|DynamicReference
     */
    public function getClonedFrom()
    {
        return $this->container['clonedFrom'];
    }

    /**
     * Sets clonedFrom.
     *
     * @param null|DynamicReference $clonedFrom clonedFrom
     *
     * @return self
     */
    public function setClonedFrom($clonedFrom)
    {
        if (is_null($clonedFrom)) {
            array_push($this->openAPINullablesSetToNull, 'clonedFrom');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('clonedFrom', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['clonedFrom'] = $clonedFrom;

        return $this;
    }

    /**
     * Gets versionId.
     *
     * @return string
     */
    public function getVersionId()
    {
        return $this->container['versionId'];
    }

    /**
     * Sets versionId.
     *
     * @param string $versionId versionId
     *
     * @return self
     */
    public function setVersionId($versionId)
    {
        if (is_null($versionId)) {
            throw new \InvalidArgumentException('non-nullable versionId cannot be null');
        }
        $this->container['versionId'] = $versionId;

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
