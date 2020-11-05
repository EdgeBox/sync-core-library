<?php

namespace EdgeBox\SyncCore\V1\Storage;

/**
 * Class ObjectStorage
 * Implement Storage for the Sync Core "ObjectStorage" entity type.
 *
 * @package Drupal\cms_content_sync\SyncCore\V1\Storage
 */
class ObjectStorage extends Storage {
  const ID = 'api_unify-api_unify-object_storage-0_1';

  const PROPERTY_TYPE = 'type';
  const PROPERTY_PROPERTIES = 'properties';

  /**
   * @inheritdoc
   */
  public function getId() {
    return self::ID;
  }

}
