<?php
/**
 * RemoteEntityDependency.
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
 * RemoteEntityDependency Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class RemoteEntityDependency implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'RemoteEntityDependency';

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
        'versionId' => 'string',
        'versionIdWithTranslations' => 'string',
        'viewUrl' => 'string',
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
        'versionId' => null,
        'versionIdWithTranslations' => null,
        'viewUrl' => null,
    ];

    /**
     * Array of nullable properties. Used for (de)serialization.
     *
     * @var bool[]
     */
    protected static array $openAPINullables = [
        'remoteUuid' => true,
        'remoteUniqueId' => true,
        'language' => false,
        'entityTypeNamespaceMachineName' => false,
        'entityTypeMachineName' => false,
        'entityTypeVersion' => false,
        'poolMachineNames' => false,
        'referenceDetails' => true,
        'name' => true,
        'versionId' => true,
        'versionIdWithTranslations' => true,
        'viewUrl' => true,
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
        'remoteUuid' => 'remoteUuid',
        'remoteUniqueId' => 'remoteUniqueId',
        'language' => 'language',
        'entityTypeNamespaceMachineName' => 'entityTypeNamespaceMachineName',
        'entityTypeMachineName' => 'entityTypeMachineName',
        'entityTypeVersion' => 'entityTypeVersion',
        'poolMachineNames' => 'poolMachineNames',
        'referenceDetails' => 'referenceDetails',
        'name' => 'name',
        'versionId' => 'versionId',
        'versionIdWithTranslations' => 'versionIdWithTranslations',
        'viewUrl' => 'viewUrl',
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
        'versionId' => 'setVersionId',
        'versionIdWithTranslations' => 'setVersionIdWithTranslations',
        'viewUrl' => 'setViewUrl',
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
        'versionId' => 'getVersionId',
        'versionIdWithTranslations' => 'getVersionIdWithTranslations',
        'viewUrl' => 'getViewUrl',
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
        $this->setIfExists('remoteUuid', $data ?? [], null);
        $this->setIfExists('remoteUniqueId', $data ?? [], null);
        $this->setIfExists('language', $data ?? [], null);
        $this->setIfExists('entityTypeNamespaceMachineName', $data ?? [], null);
        $this->setIfExists('entityTypeMachineName', $data ?? [], null);
        $this->setIfExists('entityTypeVersion', $data ?? [], null);
        $this->setIfExists('poolMachineNames', $data ?? [], null);
        $this->setIfExists('referenceDetails', $data ?? [], null);
        $this->setIfExists('name', $data ?? [], null);
        $this->setIfExists('versionId', $data ?? [], null);
        $this->setIfExists('versionIdWithTranslations', $data ?? [], null);
        $this->setIfExists('viewUrl', $data ?? [], null);
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
        if (is_null($entityTypeNamespaceMachineName)) {
            throw new \InvalidArgumentException('non-nullable entityTypeNamespaceMachineName cannot be null');
        }
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
        if (is_null($entityTypeMachineName)) {
            throw new \InvalidArgumentException('non-nullable entityTypeMachineName cannot be null');
        }
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
        if (is_null($entityTypeVersion)) {
            throw new \InvalidArgumentException('non-nullable entityTypeVersion cannot be null');
        }
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
        if (is_null($poolMachineNames)) {
            throw new \InvalidArgumentException('non-nullable poolMachineNames cannot be null');
        }
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
        if (is_null($referenceDetails)) {
            array_push($this->openAPINullablesSetToNull, 'referenceDetails');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('referenceDetails', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
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
     * Gets versionId.
     *
     * @return null|string
     */
    public function getVersionId()
    {
        return $this->container['versionId'];
    }

    /**
     * Sets versionId.
     *
     * @param null|string $versionId versionId
     *
     * @return self
     */
    public function setVersionId($versionId)
    {
        if (is_null($versionId)) {
            array_push($this->openAPINullablesSetToNull, 'versionId');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('versionId', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['versionId'] = $versionId;

        return $this;
    }

    /**
     * Gets versionIdWithTranslations.
     *
     * @return null|string
     */
    public function getVersionIdWithTranslations()
    {
        return $this->container['versionIdWithTranslations'];
    }

    /**
     * Sets versionIdWithTranslations.
     *
     * @param null|string $versionIdWithTranslations versionIdWithTranslations
     *
     * @return self
     */
    public function setVersionIdWithTranslations($versionIdWithTranslations)
    {
        if (is_null($versionIdWithTranslations)) {
            array_push($this->openAPINullablesSetToNull, 'versionIdWithTranslations');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('versionIdWithTranslations', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['versionIdWithTranslations'] = $versionIdWithTranslations;

        return $this;
    }

    /**
     * Gets viewUrl.
     *
     * @return null|string
     */
    public function getViewUrl()
    {
        return $this->container['viewUrl'];
    }

    /**
     * Sets viewUrl.
     *
     * @param null|string $viewUrl viewUrl
     *
     * @return self
     */
    public function setViewUrl($viewUrl)
    {
        if (is_null($viewUrl)) {
            array_push($this->openAPINullablesSetToNull, 'viewUrl');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('viewUrl', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['viewUrl'] = $viewUrl;

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
