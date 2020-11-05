<?php

namespace EdgeBox\SyncCore\V1\Storage;

/**
 * Class PreviewEntityStorage
 * Implement Storage for the Sync Core "Preview" entity type.
 *
 * @package Drupal\cms_content_sync\SyncCore\V1\Storage
 */
class PreviewEntityStorage extends Storage {

  /**
   * @var string EXTERNAL_PREVIEW_PATH
   *   The path to find the preview entities at.
   */
  const EXTERNAL_PREVIEW_PATH = 'drupal/cms-content-sync/preview';

  /**
   * @var string PREVIEW_ENTITY_ID
   *   The entity type ID from Sync Core used to store preview entities as.
   */
  const PREVIEW_ENTITY_ID = 'drupal-synchronization-entity_preview-0_1';

  /**
   * @var string PREVIEW_CONNECTION_ID
   *   The unique connection ID in Sync Core used to store preview entities at.
   */
  const ID = 'drupal_cms-content-sync_preview';

  const PROPERTY_PUBLISHED_DATE = 'published_date';
  const PROPERTY_PREVIEW_HTML = 'preview_html';
  const PROPERTY_TITLE = 'title';
  const PROPERTY_CUSTOM_PROPERTIES = 'fields';
  const PROPERTY_ENTITY_TYPE_ID = 'entity_type_id';
  const PROPERTY_ENTITY_TYPE_UNVERSIONED = 'entity_type_id_unversioned';

  /**
   * @var string PREVIEW_ENTITY_VERSION
   *   The preview entity version (see above).
   */
  const PREVIEW_ENTITY_VERSION = '0.1';

  /**
   * @var string PUBLIC_ACCESS_OPTION_NAME
   *    Option name to allow direct Sync Core communication.
   */
  const PUBLIC_ACCESS_OPTION_NAME = 'allow_public_access';

  /**
   * @inheritdoc
   */
  public function getId() {
    return self::ID;
  }

  /**
   * @inheritdoc
   */
  public function getPath() {
    return '/' . self::EXTERNAL_PREVIEW_PATH;
  }

}
