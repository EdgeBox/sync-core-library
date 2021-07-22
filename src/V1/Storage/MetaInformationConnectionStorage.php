<?php

namespace EdgeBox\SyncCore\V1\Storage;

/**
 * Class MetaInformationConnectionStorage
 * Implement Storage for the Sync Core "Meta Information per Connection" entity
 * type.
 */
class MetaInformationConnectionStorage extends Storage
{
    public const ID = 'api_unify-api_unify-entity_meta_information_connection-0_1';

    public const PROPERTY_ENTITY_ID = 'entity_id';

    public const PROPERTY_CONNECTION_ID = 'connection_id';

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return self::ID;
    }
}
