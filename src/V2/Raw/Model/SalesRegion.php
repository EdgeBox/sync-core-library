<?php
/**
 * SalesRegion.
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

/**
 * SalesRegion Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 */
class SalesRegion
{
    /**
     * Possible values of this enum.
     */
    public const NORTH_AMERICA = 'NorthAmerica';

    public const SOUTH_AMERICA = 'SouthAmerica';

    public const EUROPEAN_UNION = 'EuropeanUnion';

    public const EUROPE = 'Europe';

    public const MIDDLE_EAST = 'MiddleEast';

    public const CHINA = 'China';

    public const EAST_ASIA = 'EastAsia';

    public const AUSTRALASIA = 'Australasia';

    public const AFRICA = 'Africa';

    public const OTHER = 'Other';

    /**
     * Gets allowable values of the enum.
     *
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::NORTH_AMERICA,
            self::SOUTH_AMERICA,
            self::EUROPEAN_UNION,
            self::EUROPE,
            self::MIDDLE_EAST,
            self::CHINA,
            self::EAST_ASIA,
            self::AUSTRALASIA,
            self::AFRICA,
            self::OTHER,
        ];
    }
}
