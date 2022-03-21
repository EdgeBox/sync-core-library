<?php
/**
 * RemoteEntityTypePropertyType.
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
 * RemoteEntityTypePropertyType Class Doc Comment.
 *
 * @category Class
 *
 * @author   OpenAPI Generator team
 *
 * @see     https://openapi-generator.tech
 */
class RemoteEntityTypePropertyType
{
    /**
     * Possible values of this enum.
     */
    public const BOOLEAN = 'boolean';

    public const INTEGER = 'integer';

    public const FLOAT = 'float';

    public const STRING = 'string';

    public const OBJECT = 'object';

    public const REFERENCE = 'reference';

    public const FILE = 'file';

    /**
     * Gets allowable values of the enum.
     *
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::BOOLEAN,
            self::INTEGER,
            self::FLOAT,
            self::STRING,
            self::OBJECT,
            self::REFERENCE,
            self::FILE,
        ];
    }
}
