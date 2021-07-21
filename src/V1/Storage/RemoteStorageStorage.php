<?php

namespace EdgeBox\SyncCore\V1\Storage;

/**
 * Class RemoteStorageStorage
 * Implement Storage for the Sync Core "Remote Storage" entity type.
 */
class RemoteStorageStorage extends Storage
{
    public const ID = 'api_unify-api_unify-remote_storage-0_1';

    /**
     * Get the Sync Core connection ID for the given entity type config.
     *
     * @param string $api_id
     *                        API ID from this config
     * @param string $site_id
     *                        ID from this site from this config
     *
     * @return string a unique connection ID
     */
    public static function getStorageId($api_id, $site_id)
    {
        return sprintf(
            'drupal-%s-%s',
            $api_id,
            $site_id
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return self::ID;
    }
}
