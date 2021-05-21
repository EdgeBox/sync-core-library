<?php

namespace EdgeBox\SyncCore\V1\Storage;

/**
 * Class PreviewEntityStorage
 * Implement Storage for the Sync Core "Preview" entity type.
 */
class PreviewEntityStorage extends Storage
{
    /**
     * @var string EXTERNAL_PREVIEW_PATH
     *             The path to find the preview entities at
     */
    public const EXTERNAL_PREVIEW_PATH = 'drupal/cms-content-sync/preview';

    /**
     * @var string PREVIEW_ENTITY_ID
     *             The entity type ID from Sync Core used to store preview entities as
     */
    public const PREVIEW_ENTITY_ID = 'drupal-synchronization-entity_preview-0_1';

    /**
     * @var string PREVIEW_CONNECTION_ID
     *             The unique connection ID in Sync Core used to store preview entities at
     */
    public const ID = 'drupal_cms-content-sync_preview';

    public const PROPERTY_PUBLISHED_DATE = 'published_date';

    public const PROPERTY_PREVIEW_HTML = 'preview_html';

    public const PROPERTY_TITLE = 'title';

    public const PROPERTY_CUSTOM_PROPERTIES = 'fields';

    public const PROPERTY_ENTITY_TYPE_ID = 'entity_type_id';

    public const PROPERTY_ENTITY_TYPE_UNVERSIONED = 'entity_type_id_unversioned';

    /**
     * @var string PREVIEW_ENTITY_VERSION
     *             The preview entity version (see above)
     */
    public const PREVIEW_ENTITY_VERSION = '0.1';

    /**
     * @var string PUBLIC_ACCESS_OPTION_NAME
     *             Option name to allow direct Sync Core communication
     */
    public const PUBLIC_ACCESS_OPTION_NAME = 'allow_public_access';

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return self::ID;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return '/'.self::EXTERNAL_PREVIEW_PATH;
    }
}
