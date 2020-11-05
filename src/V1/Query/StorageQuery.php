<?php

namespace SyncCore\V1\Query;

/**
 * Class StorageQuery.
 *
 * A query to execute against a specific storage of the Sync Core. Will return a Result object when
 * executed. This is just a simple helper class to simplify query creation in an OOP fashion.
 *
 * @package Drupal\cms_content_sync\SyncCore
 */
abstract class StorageQuery extends Query {

  /**
   * @var \Drupal\cms_content_sync\SyncCore\V1\Storage\Storage
   */
  protected $storage;

  /**
   * Query constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\Storage\Storage $storage
   */
  public function __construct($storage) {
    parent::__construct($storage->getClient());

    $this->storage = $storage;
  }

  /**
   * Get the Storage the Query belongs to.
   *
   * @return \Drupal\cms_content_sync\SyncCore\V1\Storage\Storage
   */
  public function getStorage() {
    return $this->storage;
  }

  /**
   * Get a RequestArguments instance.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\Storage\Storage $storage
   *
   * @return \Drupal\cms_content_sync\SyncCore\V1\Query\Query
   */
  abstract public static function create($storage);

}
