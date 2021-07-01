<?php

namespace EdgeBox\SyncCore\V1;

use EdgeBox\SyncCore\V1\Storage\ApiStorage;
use EdgeBox\SyncCore\V1\Storage\ConnectionStorage;
use EdgeBox\SyncCore\V1\Storage\ConnectionSynchronizationStorage;
use EdgeBox\SyncCore\V1\Storage\CustomStorage;
use EdgeBox\SyncCore\V1\Storage\EntityTypeStorage;
use EdgeBox\SyncCore\V1\Storage\InstanceStorage;
use EdgeBox\SyncCore\V1\Storage\MetaInformationConnectionStorage;
use EdgeBox\SyncCore\V1\Storage\ObjectStorage;
use EdgeBox\SyncCore\V1\Storage\PreviewEntityStorage;
use EdgeBox\SyncCore\V1\Storage\RemoteStorageStorage;

/**
 * Class Storages.
 *
 * Wrapper class providing all of the different storages from the Sync Core.
 *
 * @package Drupal\cms_content_sync\SyncCore\V1
 */
class Storage {

  /**
   * @var \EdgeBox\SyncCore\V1\SyncCore
   */
  protected $core;

  /**
   * Storages constructor.
   *
   * @param \EdgeBox\SyncCore\V1\SyncCore $core
   */
  public function __construct($core) {
    $this->core = $core;
  }

  /**
   * @var array
   */
  protected $cache = [];

  /**
   * @param string $type
   *
   * @return \EdgeBox\SyncCore\V1\Storage\Storage
   *
   * @throws \Exception
   */
  public function getStorageById($type) {
    if ($type == MetaInformationConnectionStorage::ID) {
      return $this->getMetaInformationConnectionStorage();
    }
    if ($type == ConnectionStorage::ID) {
      return $this->getConnectionStorage();
    }
    if ($type == EntityTypeStorage::ID) {
      return $this->getEntityTypeStorage();
    }
    if ($type == ConnectionSynchronizationStorage::ID) {
      return $this->getConnectionSynchronizationStorage();
    }
    if ($type == PreviewEntityStorage::ID) {
      return $this->getPreviewEntityStorage();
    }
    if ($type == InstanceStorage::ID) {
      return $this->getInstanceStorage();
    }
    if ($type == ApiStorage::ID) {
      return $this->getApiStorage();
    }
    if ($type == ObjectStorage::ID) {
      return $this->getObjectStorage();
    }
    if ($type == RemoteStorageStorage::ID) {
      return $this->getRemoteStorage();
    }

    throw new \Exception("Unknown storage type $type.");
  }

  /**
   * @return \EdgeBox\SyncCore\V1\Storage\MetaInformationConnectionStorage
   */
  public function getMetaInformationConnectionStorage() {
    if (!empty($this->cache[MetaInformationConnectionStorage::ID])) {
      return $this->cache[MetaInformationConnectionStorage::ID];
    }
    return $this->cache[MetaInformationConnectionStorage::ID] = new MetaInformationConnectionStorage($this->core);
  }

  /**
   * @return \EdgeBox\SyncCore\V1\Storage\ConnectionStorage
   */
  public function getConnectionStorage() {
    if (!empty($this->cache[ConnectionStorage::ID])) {
      return $this->cache[ConnectionStorage::ID];
    }
    return $this->cache[ConnectionStorage::ID] = new ConnectionStorage($this->core);
  }

  /**
   * @return \EdgeBox\SyncCore\V1\Storage\EntityTypeStorage
   */
  public function getEntityTypeStorage() {
    if (!empty($this->cache[EntityTypeStorage::ID])) {
      return $this->cache[EntityTypeStorage::ID];
    }
    return $this->cache[EntityTypeStorage::ID] = new EntityTypeStorage($this->core);
  }

  /**
   * @return \EdgeBox\SyncCore\V1\Storage\ConnectionSynchronizationStorage
   */
  public function getConnectionSynchronizationStorage() {
    if (!empty($this->cache[ConnectionSynchronizationStorage::ID])) {
      return $this->cache[ConnectionSynchronizationStorage::ID];
    }
    return $this->cache[ConnectionSynchronizationStorage::ID] = new ConnectionSynchronizationStorage($this->core);
  }

  /**
   * @return \EdgeBox\SyncCore\V1\Storage\PreviewEntityStorage
   */
  public function getPreviewEntityStorage() {
    if (!empty($this->cache[PreviewEntityStorage::ID])) {
      return $this->cache[PreviewEntityStorage::ID];
    }
    return $this->cache[PreviewEntityStorage::ID] = new PreviewEntityStorage($this->core);
  }

  /**
   * @return \EdgeBox\SyncCore\V1\Storage\InstanceStorage
   */
  public function getInstanceStorage() {
    if (!empty($this->cache[InstanceStorage::ID])) {
      return $this->cache[InstanceStorage::ID];
    }
    return $this->cache[InstanceStorage::ID] = new InstanceStorage($this->core);
  }

  /**
   * @return \EdgeBox\SyncCore\V1\Storage\ApiStorage
   */
  public function getApiStorage() {
    if (!empty($this->cache[ApiStorage::ID])) {
      return $this->cache[ApiStorage::ID];
    }
    return $this->cache[ApiStorage::ID] = new ApiStorage($this->core);
  }

  /**
   * @return \EdgeBox\SyncCore\V1\Storage\ObjectStorage
   */
  public function getObjectStorage() {
    if (!empty($this->cache[ObjectStorage::ID])) {
      return $this->cache[ObjectStorage::ID];
    }
    return $this->cache[ObjectStorage::ID] = new ObjectStorage($this->core);
  }

  /**
   * @return \EdgeBox\SyncCore\V1\Storage\RemoteStorageStorage
   */
  public function getRemoteStorage() {
    if (!empty($this->cache[RemoteStorageStorage::ID])) {
      return $this->cache[RemoteStorageStorage::ID];
    }
    return $this->cache[RemoteStorageStorage::ID] = new RemoteStorageStorage($this->core);
  }

  /**
   * @param string $api_id
   * @param string $site_id
   * @param string $entity_type_name
   * @param string $bundle_name
   *
   * @return \EdgeBox\SyncCore\V1\Storage\CustomStorage
   */
  public function getCustomStorage($api_id, $site_id, $entity_type_name, $bundle_name) {
    if (!empty($this->cache['custom'][$api_id][$site_id][$entity_type_name][$bundle_name])) {
      return $this->cache['custom'][$api_id][$site_id][$entity_type_name][$bundle_name];
    }

    return $this->cache['custom'][$api_id][$site_id][$entity_type_name][$bundle_name] = new CustomStorage(
      $this->core,
      $api_id,
      $site_id,
      $entity_type_name,
      $bundle_name
    );
  }

}
