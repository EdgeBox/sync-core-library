<?php
/**
 * SyndicationStatus.
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

/**
 * SyndicationStatus Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 */
class SyndicationStatus
{
    /**
     * Possible values of this enum.
     */
    public const _100_INITIALIZING = '100-initializing';
    public const _200_RUNNING = '200-running';
    public const _300_RETRYING = '300-retrying';
    public const _400_FINISHED = '400-finished';
    public const _500_FAILED = '500-failed';
    public const _600_ABORTED = '600-aborted';
    public const _700_LIMIT_EXCEEDED = '700-limit-exceeded';

    /**
     * Gets allowable values of the enum.
     *
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::_100_INITIALIZING,
            self::_200_RUNNING,
            self::_300_RETRYING,
            self::_400_FINISHED,
            self::_500_FAILED,
            self::_600_ABORTED,
            self::_700_LIMIT_EXCEEDED,
        ];
    }
}
