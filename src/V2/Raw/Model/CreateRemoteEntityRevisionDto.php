<?php
/**
 * CreateRemoteEntityRevisionDto.
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
 * CreateRemoteEntityRevisionDto Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class CreateRemoteEntityRevisionDto implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'CreateRemoteEntityRevisionDto';

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
        'embed' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbedRootDraft[]',
        'properties' => '\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityPropertyDraft[]',
        'allDependencies' => '\EdgeBox\SyncCore\V2\Raw\Model\RuntimeRemoteEntityDependencyWithDependencies[]',
        'entityTypeByMachineName' => '\EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityRevisionDtoEntityTypeByMachineName',
        'translationRoot' => 'DynamicReference',
        'previewHtmlFileId' => 'string',
        'translations' => '\EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityRevisionDto[]',
        'flowMachineName' => 'string',
        'previewHtml' => 'DynamicReference',
        'allLanguages' => 'string[]',
        'changedLanguages' => 'string[]',
        'versionId' => 'string',
        'versionIdWithTranslations' => 'string',
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
        'entityTypeByMachineName' => null,
        'translationRoot' => null,
        'previewHtmlFileId' => null,
        'translations' => null,
        'flowMachineName' => null,
        'previewHtml' => null,
        'allLanguages' => null,
        'changedLanguages' => null,
        'versionId' => null,
        'versionIdWithTranslations' => null,
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
        'entityTypeByMachineName' => false,
        'translationRoot' => true,
        'previewHtmlFileId' => true,
        'translations' => true,
        'flowMachineName' => false,
        'previewHtml' => true,
        'allLanguages' => true,
        'changedLanguages' => true,
        'versionId' => true,
        'versionIdWithTranslations' => true,
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
        'entityTypeByMachineName' => 'entityTypeByMachineName',
        'translationRoot' => 'translationRoot',
        'previewHtmlFileId' => 'previewHtmlFileId',
        'translations' => 'translations',
        'flowMachineName' => 'flowMachineName',
        'previewHtml' => 'previewHtml',
        'allLanguages' => 'allLanguages',
        'changedLanguages' => 'changedLanguages',
        'versionId' => 'versionId',
        'versionIdWithTranslations' => 'versionIdWithTranslations',
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
        'entityTypeByMachineName' => 'setEntityTypeByMachineName',
        'translationRoot' => 'setTranslationRoot',
        'previewHtmlFileId' => 'setPreviewHtmlFileId',
        'translations' => 'setTranslations',
        'flowMachineName' => 'setFlowMachineName',
        'previewHtml' => 'setPreviewHtml',
        'allLanguages' => 'setAllLanguages',
        'changedLanguages' => 'setChangedLanguages',
        'versionId' => 'setVersionId',
        'versionIdWithTranslations' => 'setVersionIdWithTranslations',
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
        'entityTypeByMachineName' => 'getEntityTypeByMachineName',
        'translationRoot' => 'getTranslationRoot',
        'previewHtmlFileId' => 'getPreviewHtmlFileId',
        'translations' => 'getTranslations',
        'flowMachineName' => 'getFlowMachineName',
        'previewHtml' => 'getPreviewHtml',
        'allLanguages' => 'getAllLanguages',
        'changedLanguages' => 'getChangedLanguages',
        'versionId' => 'getVersionId',
        'versionIdWithTranslations' => 'getVersionIdWithTranslations',
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
        $this->setIfExists('entityTypeByMachineName', $data ?? [], null);
        $this->setIfExists('translationRoot', $data ?? [], null);
        $this->setIfExists('previewHtmlFileId', $data ?? [], null);
        $this->setIfExists('translations', $data ?? [], null);
        $this->setIfExists('flowMachineName', $data ?? [], null);
        $this->setIfExists('previewHtml', $data ?? [], null);
        $this->setIfExists('allLanguages', $data ?? [], null);
        $this->setIfExists('changedLanguages', $data ?? [], null);
        $this->setIfExists('versionId', $data ?? [], null);
        $this->setIfExists('versionIdWithTranslations', $data ?? [], null);
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
        if (null === $this->container['entityTypeByMachineName']) {
            $invalidProperties[] = "'entityTypeByMachineName' can't be null";
        }
        if (null === $this->container['flowMachineName']) {
            $invalidProperties[] = "'flowMachineName' can't be null";
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
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbedRootDraft[]
     */
    public function getEmbed()
    {
        return $this->container['embed'];
    }

    /**
     * Sets embed.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityEmbedRootDraft[] $embed embed
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
     * @return \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityPropertyDraft[]
     */
    public function getProperties()
    {
        return $this->container['properties'];
    }

    /**
     * Sets properties.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityPropertyDraft[] $properties properties
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
     * Gets entityTypeByMachineName.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityRevisionDtoEntityTypeByMachineName
     */
    public function getEntityTypeByMachineName()
    {
        return $this->container['entityTypeByMachineName'];
    }

    /**
     * Sets entityTypeByMachineName.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityRevisionDtoEntityTypeByMachineName $entityTypeByMachineName entityTypeByMachineName
     *
     * @return self
     */
    public function setEntityTypeByMachineName($entityTypeByMachineName)
    {
        if (is_null($entityTypeByMachineName)) {
            throw new \InvalidArgumentException('non-nullable entityTypeByMachineName cannot be null');
        }
        $this->container['entityTypeByMachineName'] = $entityTypeByMachineName;

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
     * Gets previewHtmlFileId.
     *
     * @return null|string
     */
    public function getPreviewHtmlFileId()
    {
        return $this->container['previewHtmlFileId'];
    }

    /**
     * Sets previewHtmlFileId.
     *
     * @param null|string $previewHtmlFileId previewHtmlFileId
     *
     * @return self
     */
    public function setPreviewHtmlFileId($previewHtmlFileId)
    {
        if (is_null($previewHtmlFileId)) {
            array_push($this->openAPINullablesSetToNull, 'previewHtmlFileId');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('previewHtmlFileId', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['previewHtmlFileId'] = $previewHtmlFileId;

        return $this;
    }

    /**
     * Gets translations.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityRevisionDto[]
     */
    public function getTranslations()
    {
        return $this->container['translations'];
    }

    /**
     * Sets translations.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\CreateRemoteEntityRevisionDto[] $translations translations
     *
     * @return self
     */
    public function setTranslations($translations)
    {
        if (is_null($translations)) {
            array_push($this->openAPINullablesSetToNull, 'translations');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('translations', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['translations'] = $translations;

        return $this;
    }

    /**
     * Gets flowMachineName.
     *
     * @return string
     */
    public function getFlowMachineName()
    {
        return $this->container['flowMachineName'];
    }

    /**
     * Sets flowMachineName.
     *
     * @param string $flowMachineName flowMachineName
     *
     * @return self
     */
    public function setFlowMachineName($flowMachineName)
    {
        if (is_null($flowMachineName)) {
            throw new \InvalidArgumentException('non-nullable flowMachineName cannot be null');
        }
        $this->container['flowMachineName'] = $flowMachineName;

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
     * Gets allLanguages.
     *
     * @return null|string[]
     */
    public function getAllLanguages()
    {
        return $this->container['allLanguages'];
    }

    /**
     * Sets allLanguages.
     *
     * @param null|string[] $allLanguages allLanguages
     *
     * @return self
     */
    public function setAllLanguages($allLanguages)
    {
        if (is_null($allLanguages)) {
            array_push($this->openAPINullablesSetToNull, 'allLanguages');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('allLanguages', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['allLanguages'] = $allLanguages;

        return $this;
    }

    /**
     * Gets changedLanguages.
     *
     * @return null|string[]
     */
    public function getChangedLanguages()
    {
        return $this->container['changedLanguages'];
    }

    /**
     * Sets changedLanguages.
     *
     * @param null|string[] $changedLanguages changedLanguages
     *
     * @return self
     */
    public function setChangedLanguages($changedLanguages)
    {
        if (is_null($changedLanguages)) {
            array_push($this->openAPINullablesSetToNull, 'changedLanguages');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('changedLanguages', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['changedLanguages'] = $changedLanguages;

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
