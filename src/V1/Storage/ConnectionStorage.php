<?php

namespace EdgeBox\SyncCore\V1\Storage;

use EdgeBox\SyncCore\V1\Entity\Connection;
use EdgeBox\SyncCore\V1\Entity\EntityPreviewConnection;

/**
 * Class ConnectionStorage
 * Implement Storage for the Sync Core "Connection" entity type.
 */
class ConnectionStorage extends Storage
{
    public const ID = 'api_unify-api_unify-connection-0_1';

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return self::ID;
    }

    /**
     * @param string $id
     *
     * @return \EdgeBox\SyncCore\V1\Entity\Connection
     */
    public function getEntity($id)
    {
        if (PreviewEntityStorage::ID === $id) {
            return new EntityPreviewConnection($this, $id);
        }

        return new Connection($this, $id);
    }
}
