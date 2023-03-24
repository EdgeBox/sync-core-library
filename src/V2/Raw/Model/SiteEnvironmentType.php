<?php
/**
 * SiteEnvironmentType.
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
 * SiteEnvironmentType Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 */
class SiteEnvironmentType
{
    /**
     * Possible values of this enum.
     */
    public const TESTING = 'testing';

    public const STAGING = 'staging';

    public const PRODUCTION = 'production';

    /**
     * Gets allowable values of the enum.
     *
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::TESTING,
            self::STAGING,
            self::PRODUCTION,
        ];
    }
}
