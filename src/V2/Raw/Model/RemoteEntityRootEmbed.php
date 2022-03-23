<?php
/**
 * RemoteEntityRootEmbed.
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
 * RemoteEntityRootEmbed Class Doc Comment.
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
class RemoteEntityRootEmbed implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'RemoteEntityRootEmbed';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'remoteUuid' => 'string',
        'remoteUniqueId' => 'string',
        'language' => 'string',
        'entityTypeNamespaceMachineName' => 'string',
        'entityTypeMachineName' => 'string',
        'entityTypeVersion' => 'string',
        'poolMachineNames' => 'string[]',
        'referenceDetails' => 'mixed',
        'name' => 'string',
        'properties' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityProperty[]',
        'directDependencies' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency[]',
        'isTranslationRoot' => 'bool',
        'translations' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbed[]',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static $openAPIFormats = [
        'remoteUuid' => null,
        'remoteUniqueId' => null,
        'language' => null,
        'entityTypeNamespaceMachineName' => null,
        'entityTypeMachineName' => null,
        'entityTypeVersion' => null,
        'poolMachineNames' => null,
        'referenceDetails' => null,
        'name' => null,
        'properties' => null,
        'directDependencies' => null,
        'isTranslationRoot' => null,
        'translations' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'remoteUuid' => 'remoteUuid',
        'remoteUniqueId' => 'remoteUniqueId',
        'language' => 'language',
        'entityTypeNamespaceMachineName' => 'entityTypeNamespaceMachineName',
        'entityTypeMachineName' => 'entityTypeMachineName',
        'entityTypeVersion' => 'entityTypeVersion',
        'poolMachineNames' => 'poolMachineNames',
        'referenceDetails' => 'referenceDetails',
        'name' => 'name',
        'properties' => 'properties',
        'directDependencies' => 'directDependencies',
        'isTranslationRoot' => 'isTranslationRoot',
        'translations' => 'translations',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'remoteUuid' => 'setRemoteUuid',
        'remoteUniqueId' => 'setRemoteUniqueId',
        'language' => 'setLanguage',
        'entityTypeNamespaceMachineName' => 'setEntityTypeNamespaceMachineName',
        'entityTypeMachineName' => 'setEntityTypeMachineName',
        'entityTypeVersion' => 'setEntityTypeVersion',
        'poolMachineNames' => 'setPoolMachineNames',
        'referenceDetails' => 'setReferenceDetails',
        'name' => 'setName',
        'properties' => 'setProperties',
        'directDependencies' => 'setDirectDependencies',
        'isTranslationRoot' => 'setIsTranslationRoot',
        'translations' => 'setTranslations',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'remoteUuid' => 'getRemoteUuid',
        'remoteUniqueId' => 'getRemoteUniqueId',
        'language' => 'getLanguage',
        'entityTypeNamespaceMachineName' => 'getEntityTypeNamespaceMachineName',
        'entityTypeMachineName' => 'getEntityTypeMachineName',
        'entityTypeVersion' => 'getEntityTypeVersion',
        'poolMachineNames' => 'getPoolMachineNames',
        'referenceDetails' => 'getReferenceDetails',
        'name' => 'getName',
        'properties' => 'getProperties',
        'directDependencies' => 'getDirectDependencies',
        'isTranslationRoot' => 'getIsTranslationRoot',
        'translations' => 'getTranslations',
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
        $this->container['remoteUuid'] = $data['remoteUuid'] ?? null;
        $this->container['remoteUniqueId'] = $data['remoteUniqueId'] ?? null;
        $this->container['language'] = $data['language'] ?? null;
        $this->container['entityTypeNamespaceMachineName'] = $data['entityTypeNamespaceMachineName'] ?? null;
        $this->container['entityTypeMachineName'] = $data['entityTypeMachineName'] ?? null;
        $this->container['entityTypeVersion'] = $data['entityTypeVersion'] ?? null;
        $this->container['poolMachineNames'] = $data['poolMachineNames'] ?? null;
        $this->container['referenceDetails'] = $data['referenceDetails'] ?? null;
        $this->container['name'] = $data['name'] ?? null;
        $this->container['properties'] = $data['properties'] ?? null;
        $this->container['directDependencies'] = $data['directDependencies'] ?? null;
        $this->container['isTranslationRoot'] = $data['isTranslationRoot'] ?? null;
        $this->container['translations'] = $data['translations'] ?? null;
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

        if (null === $this->container['language']) {
            $invalidProperties[] = "'language' can't be null";
        }
        if (null === $this->container['entityTypeNamespaceMachineName']) {
            $invalidProperties[] = "'entityTypeNamespaceMachineName' can't be null";
        }
        if (null === $this->container['entityTypeMachineName']) {
            $invalidProperties[] = "'entityTypeMachineName' can't be null";
        }
        if (null === $this->container['entityTypeVersion']) {
            $invalidProperties[] = "'entityTypeVersion' can't be null";
        }
        if (null === $this->container['poolMachineNames']) {
            $invalidProperties[] = "'poolMachineNames' can't be null";
        }
        if (null === $this->container['properties']) {
            $invalidProperties[] = "'properties' can't be null";
        }
        if (null === $this->container['directDependencies']) {
            $invalidProperties[] = "'directDependencies' can't be null";
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
     * Gets entityTypeNamespaceMachineName.
     *
     * @return string
     */
    public function getEntityTypeNamespaceMachineName()
    {
        return $this->container['entityTypeNamespaceMachineName'];
    }

    /**
     * Sets entityTypeNamespaceMachineName.
     *
     * @param string $entityTypeNamespaceMachineName entityTypeNamespaceMachineName
     *
     * @return self
     */
    public function setEntityTypeNamespaceMachineName($entityTypeNamespaceMachineName)
    {
        $this->container['entityTypeNamespaceMachineName'] = $entityTypeNamespaceMachineName;

        return $this;
    }

    /**
     * Gets entityTypeMachineName.
     *
     * @return string
     */
    public function getEntityTypeMachineName()
    {
        return $this->container['entityTypeMachineName'];
    }

    /**
     * Sets entityTypeMachineName.
     *
     * @param string $entityTypeMachineName entityTypeMachineName
     *
     * @return self
     */
    public function setEntityTypeMachineName($entityTypeMachineName)
    {
        $this->container['entityTypeMachineName'] = $entityTypeMachineName;

        return $this;
    }

    /**
     * Gets entityTypeVersion.
     *
     * @return string
     */
    public function getEntityTypeVersion()
    {
        return $this->container['entityTypeVersion'];
    }

    /**
     * Sets entityTypeVersion.
     *
     * @param string $entityTypeVersion entityTypeVersion
     *
     * @return self
     */
    public function setEntityTypeVersion($entityTypeVersion)
    {
        $this->container['entityTypeVersion'] = $entityTypeVersion;

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
     * Gets referenceDetails.
     *
     * @return null|mixed
     */
    public function getReferenceDetails()
    {
        return $this->container['referenceDetails'];
    }

    /**
     * Sets referenceDetails.
     *
     * @param null|mixed $referenceDetails referenceDetails
     *
     * @return self
     */
    public function setReferenceDetails($referenceDetails)
    {
        $this->container['referenceDetails'] = $referenceDetails;

        return $this;
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
        $this->container['name'] = $name;

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
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency[]
     */
    public function getDirectDependencies()
    {
        return $this->container['directDependencies'];
    }

    /**
     * Sets directDependencies.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityDependency[] $directDependencies directDependencies
     *
     * @return self
     */
    public function setDirectDependencies($directDependencies)
    {
        $this->container['directDependencies'] = $directDependencies;

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
        $this->container['isTranslationRoot'] = $isTranslationRoot;

        return $this;
    }

    /**
     * Gets translations.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbed[]
     */
    public function getTranslations()
    {
        return $this->container['translations'];
    }

    /**
     * Sets translations.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbed[] $translations translations
     *
     * @return self
     */
    public function setTranslations($translations)
    {
        $this->container['translations'] = $translations;

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
