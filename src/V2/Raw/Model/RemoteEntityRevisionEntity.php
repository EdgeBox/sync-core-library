<?php
/**
 * RemoteEntityRevisionEntity.
 *
 * PHP version 7.2
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
 * OpenAPI Generator version: 5.2.0-SNAPSHOT
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
 * @implements \ArrayAccess<TKey, TValue>
 * @template TKey int|null
 * @template TValue mixed|null
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
        'properties' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityProperty[]',
        'directDependencies' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency[]',
        'appType' => '\EdgeBox\SyncCore\V2\Raw\Model\SiteApplicationType',
        'embed' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbed[]',
        'poolMachineNames' => 'string[]',
        'isTranslationRoot' => 'bool',
        'viewUrl' => 'string',
        'allDependencies' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependencies[]',
        'entityType' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'entityTypeVersion' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'pools' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]',
        'customer' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'project' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'translationRoot' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'previewHtml' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'id' => 'string',
        'createdAt' => 'float',
        'updatedAt' => 'float',
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
        'properties' => null,
        'directDependencies' => null,
        'appType' => null,
        'embed' => null,
        'poolMachineNames' => null,
        'isTranslationRoot' => null,
        'viewUrl' => null,
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
        'versionId' => null,
    ];

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
     * @var string[]
     */
    protected static $attributeMap = [
        'name' => 'name',
        'remoteUuid' => 'remoteUuid',
        'remoteUniqueId' => 'remoteUniqueId',
        'language' => 'language',
        'properties' => 'properties',
        'directDependencies' => 'directDependencies',
        'appType' => 'appType',
        'embed' => 'embed',
        'poolMachineNames' => 'poolMachineNames',
        'isTranslationRoot' => 'isTranslationRoot',
        'viewUrl' => 'viewUrl',
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
        'properties' => 'setProperties',
        'directDependencies' => 'setDirectDependencies',
        'appType' => 'setAppType',
        'embed' => 'setEmbed',
        'poolMachineNames' => 'setPoolMachineNames',
        'isTranslationRoot' => 'setIsTranslationRoot',
        'viewUrl' => 'setViewUrl',
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
        'properties' => 'getProperties',
        'directDependencies' => 'getDirectDependencies',
        'appType' => 'getAppType',
        'embed' => 'getEmbed',
        'poolMachineNames' => 'getPoolMachineNames',
        'isTranslationRoot' => 'getIsTranslationRoot',
        'viewUrl' => 'getViewUrl',
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
        'versionId' => 'getVersionId',
    ];

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
        $this->container['name'] = $data['name'] ?? null;
        $this->container['remoteUuid'] = $data['remoteUuid'] ?? null;
        $this->container['remoteUniqueId'] = $data['remoteUniqueId'] ?? null;
        $this->container['language'] = $data['language'] ?? null;
        $this->container['properties'] = $data['properties'] ?? null;
        $this->container['directDependencies'] = $data['directDependencies'] ?? null;
        $this->container['appType'] = $data['appType'] ?? null;
        $this->container['embed'] = $data['embed'] ?? null;
        $this->container['poolMachineNames'] = $data['poolMachineNames'] ?? null;
        $this->container['isTranslationRoot'] = $data['isTranslationRoot'] ?? null;
        $this->container['viewUrl'] = $data['viewUrl'] ?? null;
        $this->container['allDependencies'] = $data['allDependencies'] ?? null;
        $this->container['entityType'] = $data['entityType'] ?? null;
        $this->container['entityTypeVersion'] = $data['entityTypeVersion'] ?? null;
        $this->container['pools'] = $data['pools'] ?? null;
        $this->container['customer'] = $data['customer'] ?? null;
        $this->container['project'] = $data['project'] ?? null;
        $this->container['translationRoot'] = $data['translationRoot'] ?? null;
        $this->container['previewHtml'] = $data['previewHtml'] ?? null;
        $this->container['id'] = $data['id'] ?? null;
        $this->container['createdAt'] = $data['createdAt'] ?? null;
        $this->container['updatedAt'] = $data['updatedAt'] ?? null;
        $this->container['versionId'] = $data['versionId'] ?? null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if (null === $this->container['remoteUuid']) {
            $invalidProperties[] = "'remoteUuid' can't be null";
        }
        if (null === $this->container['language']) {
            $invalidProperties[] = "'language' can't be null";
        }
        if (null === $this->container['properties']) {
            $invalidProperties[] = "'properties' can't be null";
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
     * @return string|null
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name.
     *
     * @param string|null $name name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets remoteUuid.
     *
     * @return string
     */
    public function getRemoteUuid()
    {
        return $this->container['remoteUuid'];
    }

    /**
     * Sets remoteUuid.
     *
     * @param string $remoteUuid remoteUuid
     *
     * @return self
     */
    public function setRemoteUuid($remoteUuid)
    {
        $this->container['remoteUuid'] = $remoteUuid;

        return $this;
    }

    /**
     * Gets remoteUniqueId.
     *
     * @return string|null
     */
    public function getRemoteUniqueId()
    {
        return $this->container['remoteUniqueId'];
    }

    /**
     * Sets remoteUniqueId.
     *
     * @param string|null $remoteUniqueId remoteUniqueId
     *
     * @return self
     */
    public function setRemoteUniqueId($remoteUniqueId)
    {
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
        $this->container['language'] = $language;

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
        $this->container['properties'] = $properties;

        return $this;
    }

    /**
     * Gets directDependencies.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency[]|null
     */
    public function getDirectDependencies()
    {
        return $this->container['directDependencies'];
    }

    /**
     * Sets directDependencies.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency[]|null $directDependencies directDependencies
     *
     * @return self
     */
    public function setDirectDependencies($directDependencies)
    {
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
        $this->container['appType'] = $appType;

        return $this;
    }

    /**
     * Gets embed.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbed[]|null
     */
    public function getEmbed()
    {
        return $this->container['embed'];
    }

    /**
     * Sets embed.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbed[]|null $embed embed
     *
     * @return self
     */
    public function setEmbed($embed)
    {
        $this->container['embed'] = $embed;

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
        $this->container['poolMachineNames'] = $poolMachineNames;

        return $this;
    }

    /**
     * Gets isTranslationRoot.
     *
     * @return bool|null
     */
    public function getIsTranslationRoot()
    {
        return $this->container['isTranslationRoot'];
    }

    /**
     * Sets isTranslationRoot.
     *
     * @param bool|null $isTranslationRoot isTranslationRoot
     *
     * @return self
     */
    public function setIsTranslationRoot($isTranslationRoot)
    {
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
        $this->container['viewUrl'] = $viewUrl;

        return $this;
    }

    /**
     * Gets allDependencies.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependencies[]|null
     */
    public function getAllDependencies()
    {
        return $this->container['allDependencies'];
    }

    /**
     * Sets allDependencies.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependencies[]|null $allDependencies allDependencies
     *
     * @return self
     */
    public function setAllDependencies($allDependencies)
    {
        $this->container['allDependencies'] = $allDependencies;

        return $this;
    }

    /**
     * Gets entityType.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getEntityType()
    {
        return $this->container['entityType'];
    }

    /**
     * Sets entityType.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $entityType entityType
     *
     * @return self
     */
    public function setEntityType($entityType)
    {
        $this->container['entityType'] = $entityType;

        return $this;
    }

    /**
     * Gets entityTypeVersion.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference
     */
    public function getEntityTypeVersion()
    {
        return $this->container['entityTypeVersion'];
    }

    /**
     * Sets entityTypeVersion.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference $entityTypeVersion entityTypeVersion
     *
     * @return self
     */
    public function setEntityTypeVersion($entityTypeVersion)
    {
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
        $this->container['pools'] = $pools;

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
     * Gets project.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference|null
     */
    public function getProject()
    {
        return $this->container['project'];
    }

    /**
     * Sets project.
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
     * Gets translationRoot.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference|null
     */
    public function getTranslationRoot()
    {
        return $this->container['translationRoot'];
    }

    /**
     * Sets translationRoot.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference|null $translationRoot translationRoot
     *
     * @return self
     */
    public function setTranslationRoot($translationRoot)
    {
        $this->container['translationRoot'] = $translationRoot;

        return $this;
    }

    /**
     * Gets previewHtml.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference|null
     */
    public function getPreviewHtml()
    {
        return $this->container['previewHtml'];
    }

    /**
     * Sets previewHtml.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\DynamicReference|null $previewHtml previewHtml
     *
     * @return self
     */
    public function setPreviewHtml($previewHtml)
    {
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
        $this->container['versionId'] = $versionId;

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
     * @param int $offset Offset
     *
     * @return void
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
     *               of any type other than a resource
     */
    public function jsonSerialize()
    {
        return ObjectSerializer::sanitizeForSerialization($this);
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
     * Gets a header-safe presentation of the object.
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}
