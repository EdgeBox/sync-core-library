<?php

namespace EdgeBox\SyncCore\V1\Storage;

/**
 * Class InstanceStorage
 * Implement Storage for the Sync Core "Instance" entity type.
 */
class InstanceStorage extends Storage
{
    /**
     * Static ID of this entity type and connection.
     */
    public const ID = 'api_unify-api_unify-instance-0_1';

    /**
     * Base URL of the site.
     */
    public const PROPERTY_BASE_URL = 'base_url';

    /**
     * @var string POOL_SITE_ID
     *             The virtual site id for the pool and it's connections / synchronizations
     */
    public const POOL_SITE_ID = '_pool';

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return self::ID;
    }
}
