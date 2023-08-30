<?php
/**
 * UsageStatsType.
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

/**
 * UsageStatsType Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 */
class UsageStatsType
{
    /**
     * Possible values of this enum.
     */
    public const SITE_HOURLY = 'site.hourly';

    public const SITE_MONTHLY = 'site.monthly';

    public const PROJECT_MONTHLY = 'project.monthly';

    public const CONTRACT_HOURLY = 'contract.hourly';

    public const CONTRACT_MONTHLY = 'contract.monthly';

    public const CUSTOMER_HOURLY = 'customer.hourly';

    /**
     * Gets allowable values of the enum.
     *
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::SITE_HOURLY,
            self::SITE_MONTHLY,
            self::PROJECT_MONTHLY,
            self::CONTRACT_HOURLY,
            self::CONTRACT_MONTHLY,
            self::CUSTOMER_HOURLY,
        ];
    }
}
