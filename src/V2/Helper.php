<?php

namespace EdgeBox\SyncCore\V2;

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
     * Remove any information about basic auth in any URLs contained in the given messages.
     *
     * @param string|array $message
     *
     * @return array|string|string[]|null
     */
    public static function obfuscateCredentials($message)
    {
        if (is_array($message)) {
            if (isset($message['msg'])) {
                $message['msg'] = self::obfuscateCredentials($message['msg']);
            } elseif (isset($message['err']['message'])) {
                $message['err']['message'] = self::obfuscateCredentials($message['err']['message']);
            }
            /*
             * Ignore other associative arrays.
             */
            elseif (isset($message[0])) {
                for ($i = 0; $i < count($message); ++$i) {
                    $message[$i] = self::obfuscateCredentials($message[$i]);
                }
            }

            return $message;
        }

        $message = preg_replace('@https://([^:]+):([^\@]+)\@@i', 'https://$1:****@', $message);
        $message = preg_replace('@http://([^:]+):([^\@]+)\@@i', 'http://$1:****@', $message);

        return $message;
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
