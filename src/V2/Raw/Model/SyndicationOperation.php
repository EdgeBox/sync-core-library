<?php
/**
 * SyndicationOperation.
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
 * SyndicationOperation Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class SyndicationOperation implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'SyndicationOperation';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'status' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationStatus',
        'type' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationOperationType',
        'subType' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationOperationSubType',
        'page' => 'float',
        'errors' => '\EdgeBox\SyncCore\V2\Raw\Model\SyndicationError[]',
        'entity' => 'DynamicReference',
        'file' => 'DynamicReference',
        'pools' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference[]',
        'language' => 'string',
        'isTranslationRoot' => 'bool',
        'cloneExisting' => 'bool',
        'siteRequests' => 'float',
        'startTime' => 'float',
        'requestDuration' => 'float',
        'slowestRequestDuration' => 'float',
        'traceRequestDetails' => 'DynamicReference',
        'updateCount' => 'float',
        'ignoreCount' => 'float',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     * @phpstan-var array<string, string|null>
     * @psalm-var array<string, string|null>
     */
    protected static $openAPIFormats = [
        'status' => null,
        'type' => null,
        'subType' => null,
        'page' => null,
        'errors' => null,
        'entity' => null,
        'file' => null,
        'pools' => null,
        'language' => null,
        'isTranslationRoot' => null,
        'cloneExisting' => null,
        'siteRequests' => null,
        'startTime' => null,
        'requestDuration' => null,
        'slowestRequestDuration' => null,
        'traceRequestDetails' => null,
        'updateCount' => null,
        'ignoreCount' => null,
    ];

    /**
     * Array of nullable properties. Used for (de)serialization.
     *
     * @var bool[]
     */
    protected static array $openAPINullables = [
        'status' => false,
        'type' => false,
        'subType' => false,
        'page' => true,
        'errors' => true,
        'entity' => true,
        'file' => true,
        'pools' => true,
        'language' => true,
        'isTranslationRoot' => true,
        'cloneExisting' => true,
        'siteRequests' => true,
        'startTime' => true,
        'requestDuration' => true,
        'slowestRequestDuration' => true,
        'traceRequestDetails' => true,
        'updateCount' => true,
        'ignoreCount' => true,
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
        'status' => 'status',
        'type' => 'type',
        'subType' => 'subType',
        'page' => 'page',
        'errors' => 'errors',
        'entity' => 'entity',
        'file' => 'file',
        'pools' => 'pools',
        'language' => 'language',
        'isTranslationRoot' => 'isTranslationRoot',
        'cloneExisting' => 'cloneExisting',
        'siteRequests' => 'siteRequests',
        'startTime' => 'startTime',
        'requestDuration' => 'requestDuration',
        'slowestRequestDuration' => 'slowestRequestDuration',
        'traceRequestDetails' => 'traceRequestDetails',
        'updateCount' => 'updateCount',
        'ignoreCount' => 'ignoreCount',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'status' => 'setStatus',
        'type' => 'setType',
        'subType' => 'setSubType',
        'page' => 'setPage',
        'errors' => 'setErrors',
        'entity' => 'setEntity',
        'file' => 'setFile',
        'pools' => 'setPools',
        'language' => 'setLanguage',
        'isTranslationRoot' => 'setIsTranslationRoot',
        'cloneExisting' => 'setCloneExisting',
        'siteRequests' => 'setSiteRequests',
        'startTime' => 'setStartTime',
        'requestDuration' => 'setRequestDuration',
        'slowestRequestDuration' => 'setSlowestRequestDuration',
        'traceRequestDetails' => 'setTraceRequestDetails',
        'updateCount' => 'setUpdateCount',
        'ignoreCount' => 'setIgnoreCount',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'status' => 'getStatus',
        'type' => 'getType',
        'subType' => 'getSubType',
        'page' => 'getPage',
        'errors' => 'getErrors',
        'entity' => 'getEntity',
        'file' => 'getFile',
        'pools' => 'getPools',
        'language' => 'getLanguage',
        'isTranslationRoot' => 'getIsTranslationRoot',
        'cloneExisting' => 'getCloneExisting',
        'siteRequests' => 'getSiteRequests',
        'startTime' => 'getStartTime',
        'requestDuration' => 'getRequestDuration',
        'slowestRequestDuration' => 'getSlowestRequestDuration',
        'traceRequestDetails' => 'getTraceRequestDetails',
        'updateCount' => 'getUpdateCount',
        'ignoreCount' => 'getIgnoreCount',
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
        $this->setIfExists('status', $data ?? [], null);
        $this->setIfExists('type', $data ?? [], null);
        $this->setIfExists('subType', $data ?? [], null);
        $this->setIfExists('page', $data ?? [], null);
        $this->setIfExists('errors', $data ?? [], null);
        $this->setIfExists('entity', $data ?? [], null);
        $this->setIfExists('file', $data ?? [], null);
        $this->setIfExists('pools', $data ?? [], null);
        $this->setIfExists('language', $data ?? [], null);
        $this->setIfExists('isTranslationRoot', $data ?? [], null);
        $this->setIfExists('cloneExisting', $data ?? [], null);
        $this->setIfExists('siteRequests', $data ?? [], null);
        $this->setIfExists('startTime', $data ?? [], null);
        $this->setIfExists('requestDuration', $data ?? [], null);
        $this->setIfExists('slowestRequestDuration', $data ?? [], null);
        $this->setIfExists('traceRequestDetails', $data ?? [], null);
        $this->setIfExists('updateCount', $data ?? [], null);
        $this->setIfExists('ignoreCount', $data ?? [], null);
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
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SyndicationOperationType
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SyndicationOperationType $type type
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
     * Gets subType.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\SyndicationOperationSubType
     */
    public function getSubType()
    {
        return $this->container['subType'];
    }

    /**
     * Sets subType.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\SyndicationOperationSubType $subType subType
     *
     * @return self
     */
    public function setSubType($subType)
    {
        if (is_null($subType)) {
            throw new \InvalidArgumentException('non-nullable subType cannot be null');
        }
        $this->container['subType'] = $subType;

        return $this;
    }

    /**
     * Gets page.
     *
     * @return null|float
     */
    public function getPage()
    {
        return $this->container['page'];
    }

    /**
     * Sets page.
     *
     * @param null|float $page page
     *
     * @return self
     */
    public function setPage($page)
    {
        if (is_null($page)) {
            array_push($this->openAPINullablesSetToNull, 'page');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('page', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['page'] = $page;

        return $this;
    }

    /**
     * Gets errors.
     *
     * @return null|\EdgeBox\SyncCore\V2\Raw\Model\SyndicationError[]
     */
    public function getErrors()
    {
        return $this->container['errors'];
    }

    /**
     * Sets errors.
     *
     * @param null|\EdgeBox\SyncCore\V2\Raw\Model\SyndicationError[] $errors errors
     *
     * @return self
     */
    public function setErrors($errors)
    {
        if (is_null($errors)) {
            array_push($this->openAPINullablesSetToNull, 'errors');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('errors', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['errors'] = $errors;

        return $this;
    }

    /**
     * Gets entity.
     *
     * @return null|DynamicReference
     */
    public function getEntity()
    {
        return $this->container['entity'];
    }

    /**
     * Sets entity.
     *
     * @param null|DynamicReference $entity entity
     *
     * @return self
     */
    public function setEntity($entity)
    {
        if (is_null($entity)) {
            array_push($this->openAPINullablesSetToNull, 'entity');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('entity', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['entity'] = $entity;

        return $this;
    }

    /**
     * Gets file.
     *
     * @return null|DynamicReference
     */
    public function getFile()
    {
        return $this->container['file'];
    }

    /**
     * Sets file.
     *
     * @param null|DynamicReference $file file
     *
     * @return self
     */
    public function setFile($file)
    {
        if (is_null($file)) {
            array_push($this->openAPINullablesSetToNull, 'file');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('file', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['file'] = $file;

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
     * Gets language.
     *
     * @return null|string
     */
    public function getLanguage()
    {
        return $this->container['language'];
    }

    /**
     * Sets language.
     *
     * @param null|string $language language
     *
     * @return self
     */
    public function setLanguage($language)
    {
        if (is_null($language)) {
            array_push($this->openAPINullablesSetToNull, 'language');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('language', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['language'] = $language;

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
     * Gets cloneExisting.
     *
     * @return null|bool
     */
    public function getCloneExisting()
    {
        return $this->container['cloneExisting'];
    }

    /**
     * Sets cloneExisting.
     *
     * @param null|bool $cloneExisting cloneExisting
     *
     * @return self
     */
    public function setCloneExisting($cloneExisting)
    {
        if (is_null($cloneExisting)) {
            array_push($this->openAPINullablesSetToNull, 'cloneExisting');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('cloneExisting', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['cloneExisting'] = $cloneExisting;

        return $this;
    }

    /**
     * Gets siteRequests.
     *
     * @return null|float
     */
    public function getSiteRequests()
    {
        return $this->container['siteRequests'];
    }

    /**
     * Sets siteRequests.
     *
     * @param null|float $siteRequests siteRequests
     *
     * @return self
     */
    public function setSiteRequests($siteRequests)
    {
        if (is_null($siteRequests)) {
            array_push($this->openAPINullablesSetToNull, 'siteRequests');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('siteRequests', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['siteRequests'] = $siteRequests;

        return $this;
    }

    /**
     * Gets startTime.
     *
     * @return null|float
     */
    public function getStartTime()
    {
        return $this->container['startTime'];
    }

    /**
     * Sets startTime.
     *
     * @param null|float $startTime startTime
     *
     * @return self
     */
    public function setStartTime($startTime)
    {
        if (is_null($startTime)) {
            array_push($this->openAPINullablesSetToNull, 'startTime');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('startTime', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['startTime'] = $startTime;

        return $this;
    }

    /**
     * Gets requestDuration.
     *
     * @return null|float
     */
    public function getRequestDuration()
    {
        return $this->container['requestDuration'];
    }

    /**
     * Sets requestDuration.
     *
     * @param null|float $requestDuration requestDuration
     *
     * @return self
     */
    public function setRequestDuration($requestDuration)
    {
        if (is_null($requestDuration)) {
            array_push($this->openAPINullablesSetToNull, 'requestDuration');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('requestDuration', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['requestDuration'] = $requestDuration;

        return $this;
    }

    /**
     * Gets slowestRequestDuration.
     *
     * @return null|float
     */
    public function getSlowestRequestDuration()
    {
        return $this->container['slowestRequestDuration'];
    }

    /**
     * Sets slowestRequestDuration.
     *
     * @param null|float $slowestRequestDuration slowestRequestDuration
     *
     * @return self
     */
    public function setSlowestRequestDuration($slowestRequestDuration)
    {
        if (is_null($slowestRequestDuration)) {
            array_push($this->openAPINullablesSetToNull, 'slowestRequestDuration');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('slowestRequestDuration', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['slowestRequestDuration'] = $slowestRequestDuration;

        return $this;
    }

    /**
     * Gets traceRequestDetails.
     *
     * @return null|DynamicReference
     */
    public function getTraceRequestDetails()
    {
        return $this->container['traceRequestDetails'];
    }

    /**
     * Sets traceRequestDetails.
     *
     * @param null|DynamicReference $traceRequestDetails traceRequestDetails
     *
     * @return self
     */
    public function setTraceRequestDetails($traceRequestDetails)
    {
        if (is_null($traceRequestDetails)) {
            array_push($this->openAPINullablesSetToNull, 'traceRequestDetails');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('traceRequestDetails', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['traceRequestDetails'] = $traceRequestDetails;

        return $this;
    }

    /**
     * Gets updateCount.
     *
     * @return null|float
     */
    public function getUpdateCount()
    {
        return $this->container['updateCount'];
    }

    /**
     * Sets updateCount.
     *
     * @param null|float $updateCount updateCount
     *
     * @return self
     */
    public function setUpdateCount($updateCount)
    {
        if (is_null($updateCount)) {
            array_push($this->openAPINullablesSetToNull, 'updateCount');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('updateCount', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['updateCount'] = $updateCount;

        return $this;
    }

    /**
     * Gets ignoreCount.
     *
     * @return null|float
     */
    public function getIgnoreCount()
    {
        return $this->container['ignoreCount'];
    }

    /**
     * Sets ignoreCount.
     *
     * @param null|float $ignoreCount ignoreCount
     *
     * @return self
     */
    public function setIgnoreCount($ignoreCount)
    {
        if (is_null($ignoreCount)) {
            array_push($this->openAPINullablesSetToNull, 'ignoreCount');
        } else {
            $nullablesSetToNull = $this->getOpenAPINullablesSetToNull();
            $index = array_search('ignoreCount', $nullablesSetToNull);
            if (false !== $index) {
                unset($nullablesSetToNull[$index]);
                $this->setOpenAPINullablesSetToNull($nullablesSetToNull);
            }
        }
        $this->container['ignoreCount'] = $ignoreCount;

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
