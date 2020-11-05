<?php

namespace EdgeBox\SyncCore\V1\Storage;

/**
 * Class MetaInformationConnectionStorage
 * Implement Storage for the Sync Core "Meta Information per Connection" entity
 * type.
 *
 * @package Drupal\cms_content_sync\SyncCore\V1\Storage
 */
class MetaInformationConnectionStorage extends Storage {
  const ID = 'api_unify-api_unify-entity_meta_information_connection-0_1';

  const PROPERTY_ENTITY_ID = 'entity_id';
  const PROPERTY_CONNECTION_ID = 'connection_id';

  /**
   * @inheritdoc
   */
  public function getId() {
    return self::ID;
  }

}
