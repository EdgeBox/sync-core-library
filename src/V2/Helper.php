<?php

namespace EdgeBox\SyncCore\V2;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;

/**
 * Class Helper.
 *
 * Collection of helper functions.
 */
class Helper
{
    public static function isUuid($shared_entity_id)
    {
        return (bool) preg_match('/[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-([89ab])[a-f0-9]{3}-[a-f0-9]{12}/', $shared_entity_id);
    }

    /**
     * The scope a request is signed with: the site's configuration scope, which
     * carries its content scope, or its content scope alone.
     *
     * @return string "configuration" or "content"
     */
    public static function permissions(bool $configuration_access): string
    {
        return $configuration_access ? IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION : IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT;
    }

    /**
     * The JSON object the Sync Core answered with, as an associative array.
     *
     * An answer is read this way rather than mapped to a generated model: a
     * model declares every field as set and refuses an enum value it does not
     * declare, while the Sync Core may leave a field out or send a status newer
     * than this library. A caller reads each field with a fallback, and an
     * answer that is not a JSON object is an empty array.
     *
     * @param string $answer the response body
     *
     * @return array<string, mixed>
     */
    public static function decodeJsonObject(string $answer): array
    {
        $data = json_decode($answer, true);

        return is_array($data) ? $data : [];
    }

    public static function formatStorageSize($size, $round_up = false)
    {
        if ($round_up) {
            if ($size < 1024) {
                return $size.' Bytes';
            }
            if ($size < 1024 * 1024) {
                return ceil($size / 1024).' KB';
            }
            if ($size < 1024 * 1024 * 1024) {
                return ceil($size / 1024 / 1024).' MB';
            }

            return ceil($size / 1024 / 1024 / 1024).' GB';
        }
        if ($size < 1024) {
            return $size.' Bytes';
        }
        if ($size < 1024 * 1024) {
            return round($size / 1024).' KB';
        }
        if ($size < 1024 * 1024 * 1024) {
            return round($size / 1024 / 1024).' MB';
        }

        return round($size / 1024 / 1024 / 1024).' GB';
    }

    /**
     * Remove any information about basic auth in any URLs contained in the given messages.
     *
     * @param array|string $message
     *
     * @return null|array|string|string[]
     */
    public static function obfuscateCredentials($message)
    {
        if (is_array($message)) {
            if (isset($message['msg'])) {
                $message['msg'] = self::obfuscateCredentials($message['msg']);
            } elseif (isset($message['err']['message'])) {
                $message['err']['message'] = self::obfuscateCredentials($message['err']['message']);
            }
            // Ignore other associative arrays.
            elseif (isset($message[0])) {
                for ($i = 0; $i < count($message); ++$i) {
                    $message[$i] = self::obfuscateCredentials($message[$i]);
                }
            }

            return $message;
        }

        $message = preg_replace('@https://([^:]+):([^\@]+)\@@i', 'https://$1:****@', $message);

        return preg_replace('@http://([^:]+):([^\@]+)\@@i', 'http://$1:****@', $message);
    }

    /**
     * Create a unique hash of the given serialized entity. The hash is used to
     * check whether a referenced entity has changed. The hash is saved in the
     * serialized reference field, forcing the Sync Core to trigger syndications
     * even if only a child entity was changed.
     *
     * @param array|object $data
     *                           The serialized entity
     *
     * @return string a unique hash based on the provided array
     */
    public static function getSerializedEntityHash($data)
    {
        if (!is_array($data)) {
            $data = (array) $data;
        }

        // Ensure the order of indices doesn't matter.
        array_multisort($data);

        // json_encode is a lot faster than PHPs native serialize.
        return md5(json_encode($data));
    }
}
