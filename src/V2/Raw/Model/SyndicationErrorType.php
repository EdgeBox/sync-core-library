<?php
/**
 * SyndicationErrorType.
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
 *
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 5.0.1-SNAPSHOT
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace EdgeBox\SyncCore\V2\Raw\Model;

/**
 * SyndicationErrorType Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 */
class SyndicationErrorType
{
    /**
     * Possible values of this enum.
     */
    public const UNEXPECTED = 'unexpected';
    public const TIMEOUT = 'timeout';
    public const BAD_RESPONSE_CODE = 'bad-response-code';
    public const BAD_RESPONSE_BODY = 'bad-response-body';
    public const INVALID_DEPENDENCY = 'invalid-dependency';

    /**
     * Gets allowable values of the enum.
     *
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::UNEXPECTED,
            self::TIMEOUT,
            self::BAD_RESPONSE_CODE,
            self::BAD_RESPONSE_BODY,
            self::INVALID_DEPENDENCY,
        ];
    }
}
