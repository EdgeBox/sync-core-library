<?php

namespace EdgeBox\SyncCore\V1\Storage;

use EdgeBox\SyncCore\V1\Entity\ConnectionSynchronization;

/**
 * Class ConnectionStorage
 * Implement Storage for the Sync Core "Connection Synchronisation" entity type.
 *
 * @package Drupal\cms_content_sync\SyncCore\V1\Storage
 */
class ConnectionSynchronizationStorage extends Storage {
  const ID = 'api_unify-api_unify-connection_synchronisation-0_1';

  /**
   * Get the Sync Core connection ID for the given entity type config.
   *
   * @param string $connection_id
   *   Connection ID from self::getExternalConnectionId().
   * @param bool $is_push
   *   Push or Pull?
   *
   * @return string A unique connection ID.
   */
  public static function getExternalConnectionSynchronizationId($connection_id, $is_push) {
    return sprintf('%s--to--%s',
      $connection_id,
      $is_push ? 'pool' : 'drupal'
    );
  }

  /**
   * @inheritdoc
   */
  public function getId() {
    return self::ID;
  }

  /**
   * @param string $id
   *
   * @return \Drupal\cms_content_sync\SyncCore\V1\Entity\ConnectionSynchronization
   */
  public function getEntity($id) {
    return new ConnectionSynchronization($this, $id);
  }

}
