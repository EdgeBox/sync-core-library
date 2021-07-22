<?php

namespace EdgeBox\SyncCore\V1\Storage;

/**
 * Class ObjectStorage
 * Implement Storage for the Sync Core "ObjectStorage" entity type.
 */
class ObjectStorage extends Storage
{
    public const ID = 'api_unify-api_unify-object_storage-0_1';

    public const PROPERTY_TYPE = 'type';

    public const PROPERTY_PROPERTIES = 'properties';

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return self::ID;
    }
}
