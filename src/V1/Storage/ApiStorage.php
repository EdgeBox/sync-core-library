<?php

namespace EdgeBox\SyncCore\V1\Storage;

/**
 * Class ApiStorage
 * Implement Storage for the Sync Core "API" entity type.
 */
class ApiStorage extends Storage
{
    public const ID = 'api_unify-api_unify-api-0_1';

    public const PROPERTY_VERSION = 'version';

    public const PROPERTY_PARENT_ID = 'parent_id';

    /**
     * @var string CUSTOM_API_VERSION
     *             The API version used to identify APIs as. Breaking changes in
     *             Flow will require this version to be increased and all
     *             synchronization entities to be re-saved via update hook.
     */
    public const CUSTOM_API_VERSION = '1.0';

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return self::ID;
    }
}
