<?php

namespace EdgeBox\SyncCore\V1\Storage;

use EdgeBox\SyncCore\V1\SyncCore;

/**
 * Class EntityTypeStorage
 * Implement Storage for the actual entities being sync'd.
 *
 * @package Drupal\cms_content_sync\SyncCore\V1\Storage
 */
class EntityStorage extends Storage {

  protected $entityType;

  protected $bundle;

  protected $version;

  protected $instance_id;

  protected $api_id;

  /**
   * EntityStorage constructor. Include instance ID and API ID.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\SyncCore $client
   * @param $instance_id
   * @param $api_id
   */
  public function __construct(SyncCore $client, $instance_id, $api_id) {
    parent::__construct($client);

    $this->instance_id = $instance_id;
    $this->api_id = $api_id;
  }

  /**
   * @inheritdoc
   */
  public function getId() {
    return CustomStorage::getCustomId($this->api_id, $this->instance_id, $this->entityType, $this->bundle);
  }

}
