<?php
/**
 * ContractEntity.
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
 * OpenAPI Generator version: 5.2.0
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
 * ContractEntity Class Doc Comment.
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
class ContractEntity implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $openAPIModelName = 'ContractEntity';

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $openAPITypes = [
        'minProdSites' => 'float',
        'maxProdSites' => 'float',
        'licensedSites' => 'float',
        'currentProductionSites' => 'float',
        'currentStagingSites' => 'float',
        'currentTestingSites' => 'float',
        'autoScaleLicenses' => 'bool',
        'region' => '\EdgeBox\SyncCore\V2\Raw\Model\SalesRegion',
        'startDate' => 'float',
        'endDate' => 'float',
        'customer' => '\EdgeBox\SyncCore\V2\Raw\Model\DynamicReference',
        'uuid' => 'string',
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
        'minProdSites' => null,
        'maxProdSites' => null,
        'licensedSites' => null,
        'currentProductionSites' => null,
        'currentStagingSites' => null,
        'currentTestingSites' => null,
        'autoScaleLicenses' => null,
        'region' => null,
        'startDate' => null,
        'endDate' => null,
        'customer' => null,
        'uuid' => null,
        'id' => null,
        'createdAt' => null,
        'updatedAt' => null,
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
        'minProdSites' => 'minProdSites',
        'maxProdSites' => 'maxProdSites',
        'licensedSites' => 'licensedSites',
        'currentProductionSites' => 'currentProductionSites',
        'currentStagingSites' => 'currentStagingSites',
        'currentTestingSites' => 'currentTestingSites',
        'autoScaleLicenses' => 'autoScaleLicenses',
        'region' => 'region',
        'startDate' => 'startDate',
        'endDate' => 'endDate',
        'customer' => 'customer',
        'uuid' => 'uuid',
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
        'minProdSites' => 'setMinProdSites',
        'maxProdSites' => 'setMaxProdSites',
        'licensedSites' => 'setLicensedSites',
        'currentProductionSites' => 'setCurrentProductionSites',
        'currentStagingSites' => 'setCurrentStagingSites',
        'currentTestingSites' => 'setCurrentTestingSites',
        'autoScaleLicenses' => 'setAutoScaleLicenses',
        'region' => 'setRegion',
        'startDate' => 'setStartDate',
        'endDate' => 'setEndDate',
        'customer' => 'setCustomer',
        'uuid' => 'setUuid',
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
        'minProdSites' => 'getMinProdSites',
        'maxProdSites' => 'getMaxProdSites',
        'licensedSites' => 'getLicensedSites',
        'currentProductionSites' => 'getCurrentProductionSites',
        'currentStagingSites' => 'getCurrentStagingSites',
        'currentTestingSites' => 'getCurrentTestingSites',
        'autoScaleLicenses' => 'getAutoScaleLicenses',
        'region' => 'getRegion',
        'startDate' => 'getStartDate',
        'endDate' => 'getEndDate',
        'customer' => 'getCustomer',
        'uuid' => 'getUuid',
        'id' => 'getId',
        'createdAt' => 'getCreatedAt',
        'updatedAt' => 'getUpdatedAt',
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
        $this->container['minProdSites'] = $data['minProdSites'] ?? null;
        $this->container['maxProdSites'] = $data['maxProdSites'] ?? null;
        $this->container['licensedSites'] = $data['licensedSites'] ?? null;
        $this->container['currentProductionSites'] = $data['currentProductionSites'] ?? null;
        $this->container['currentStagingSites'] = $data['currentStagingSites'] ?? null;
        $this->container['currentTestingSites'] = $data['currentTestingSites'] ?? null;
        $this->container['autoScaleLicenses'] = $data['autoScaleLicenses'] ?? null;
        $this->container['region'] = $data['region'] ?? null;
        $this->container['startDate'] = $data['startDate'] ?? null;
        $this->container['endDate'] = $data['endDate'] ?? null;
        $this->container['customer'] = $data['customer'] ?? null;
        $this->container['uuid'] = $data['uuid'] ?? null;
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

        if (null === $this->container['licensedSites']) {
            $invalidProperties[] = "'licensedSites' can't be null";
        }
        if (null === $this->container['currentProductionSites']) {
            $invalidProperties[] = "'currentProductionSites' can't be null";
        }
        if (null === $this->container['currentStagingSites']) {
            $invalidProperties[] = "'currentStagingSites' can't be null";
        }
        if (null === $this->container['currentTestingSites']) {
            $invalidProperties[] = "'currentTestingSites' can't be null";
        }
        if (null === $this->container['autoScaleLicenses']) {
            $invalidProperties[] = "'autoScaleLicenses' can't be null";
        }
        if (null === $this->container['region']) {
            $invalidProperties[] = "'region' can't be null";
        }
        if (null === $this->container['startDate']) {
            $invalidProperties[] = "'startDate' can't be null";
        }
        if (null === $this->container['customer']) {
            $invalidProperties[] = "'customer' can't be null";
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
     * Gets minProdSites.
     *
     * @return float|null
     */
    public function getMinProdSites()
    {
        return $this->container['minProdSites'];
    }

    /**
     * Sets minProdSites.
     *
     * @param float|null $minProdSites minProdSites
     *
     * @return self
     */
    public function setMinProdSites($minProdSites)
    {
        $this->container['minProdSites'] = $minProdSites;

        return $this;
    }

    /**
     * Gets maxProdSites.
     *
     * @return float|null
     */
    public function getMaxProdSites()
    {
        return $this->container['maxProdSites'];
    }

    /**
     * Sets maxProdSites.
     *
     * @param float|null $maxProdSites maxProdSites
     *
     * @return self
     */
    public function setMaxProdSites($maxProdSites)
    {
        $this->container['maxProdSites'] = $maxProdSites;

        return $this;
    }

    /**
     * Gets licensedSites.
     *
     * @return float
     */
    public function getLicensedSites()
    {
        return $this->container['licensedSites'];
    }

    /**
     * Sets licensedSites.
     *
     * @param float $licensedSites licensedSites
     *
     * @return self
     */
    public function setLicensedSites($licensedSites)
    {
        $this->container['licensedSites'] = $licensedSites;

        return $this;
    }

    /**
     * Gets currentProductionSites.
     *
     * @return float
     */
    public function getCurrentProductionSites()
    {
        return $this->container['currentProductionSites'];
    }

    /**
     * Sets currentProductionSites.
     *
     * @param float $currentProductionSites currentProductionSites
     *
     * @return self
     */
    public function setCurrentProductionSites($currentProductionSites)
    {
        $this->container['currentProductionSites'] = $currentProductionSites;

        return $this;
    }

    /**
     * Gets currentStagingSites.
     *
     * @return float
     */
    public function getCurrentStagingSites()
    {
        return $this->container['currentStagingSites'];
    }

    /**
     * Sets currentStagingSites.
     *
     * @param float $currentStagingSites currentStagingSites
     *
     * @return self
     */
    public function setCurrentStagingSites($currentStagingSites)
    {
        $this->container['currentStagingSites'] = $currentStagingSites;

        return $this;
    }

    /**
     * Gets currentTestingSites.
     *
     * @return float
     */
    public function getCurrentTestingSites()
    {
        return $this->container['currentTestingSites'];
    }

    /**
     * Sets currentTestingSites.
     *
     * @param float $currentTestingSites currentTestingSites
     *
     * @return self
     */
    public function setCurrentTestingSites($currentTestingSites)
    {
        $this->container['currentTestingSites'] = $currentTestingSites;

        return $this;
    }

    /**
     * Gets autoScaleLicenses.
     *
     * @return bool
     */
    public function getAutoScaleLicenses()
    {
        return $this->container['autoScaleLicenses'];
    }

    /**
     * Sets autoScaleLicenses.
     *
     * @param bool $autoScaleLicenses autoScaleLicenses
     *
     * @return self
     */
    public function setAutoScaleLicenses($autoScaleLicenses)
    {
        $this->container['autoScaleLicenses'] = $autoScaleLicenses;

        return $this;
    }

    /**
     * Gets region.
     *
     * @return \EdgeBox\SyncCore\V2\Raw\Model\SalesRegion
     */
    public function getRegion()
    {
        return $this->container['region'];
    }

    /**
     * Sets region.
     *
     * @param \EdgeBox\SyncCore\V2\Raw\Model\SalesRegion $region region
     *
     * @return self
     */
    public function setRegion($region)
    {
        $this->container['region'] = $region;

        return $this;
    }

    /**
     * Gets startDate.
     *
     * @return float
     */
    public function getStartDate()
    {
        return $this->container['startDate'];
    }

    /**
     * Sets startDate.
     *
     * @param float $startDate startDate
     *
     * @return self
     */
    public function setStartDate($startDate)
    {
        $this->container['startDate'] = $startDate;

        return $this;
    }

    /**
     * Gets endDate.
     *
     * @return float|null
     */
    public function getEndDate()
    {
        return $this->container['endDate'];
    }

    /**
     * Sets endDate.
     *
     * @param float|null $endDate endDate
     *
     * @return self
     */
    public function setEndDate($endDate)
    {
        $this->container['endDate'] = $endDate;

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
