<?php

namespace EdgeBox\SyncCore\V1\Storage;

use EdgeBox\SyncCore\V1\Query\CreateItemQuery;
use EdgeBox\SyncCore\V1\Query\DeleteItemQuery;
use EdgeBox\SyncCore\V1\Query\ItemQuery;
use EdgeBox\SyncCore\V1\Query\ListQuery;
use EdgeBox\SyncCore\V1\Query\UpdateItemQuery;

/**
 * Class Storage
 * A remote entity type storage for the Sync Core. Provides Query objects for
 * the given pool and the given entity type. One Storage class is available per
 * Entity Type.
 *
 * @package Drupal\cms_content_sync\SyncCore\V1\Storage
 */
abstract class Storage {

  /**
   * ID property.
   */
  const PROPERTY_ID = 'id';

  /**
   * @var \EdgeBox\SyncCore\V1\SyncCore
   */
  protected $client;

  /**
   * Storage constructor.
   *
   * @param \EdgeBox\SyncCore\V1\SyncCore $client
   */
  public function __construct($client) {
    $this->client = $client;
  }

  /**
   * Get the path to append to the Pool::$backend_url to query entities of this
   * type.
   *
   * @return string
   */
  public function getPath() {
    return '/' . $this->getId();
  }

  /**
   * Get the entity type ID.
   *
   * @return string
   */
  abstract public function getId();

  /**
   * Get the client that this Storage belongs to.
   *
   * @return \EdgeBox\SyncCore\V1\SyncCore
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * Create and return an instance of the ListQuery.
   *
   * @return \EdgeBox\SyncCore\V1\Query\ListQuery
   */
  public function listItems() {
    return new ListQuery($this);
  }

  /**
   * Create and return an instance of the ItemQuery.
   *
   * @param string $id
   *
   * @return \EdgeBox\SyncCore\V1\Query\ItemQuery
   */
  public function getItem($id) {
    $query = new ItemQuery($this);
    $query->setEntityId($id);
    return $query;
  }

  /**
   * Create and return an instance of the CreateItemQuery.
   *
   * @param array $item
   *
   * @return \EdgeBox\SyncCore\V1\Query\CreateItemQuery
   */
  public function createItem($item) {
    $query = new CreateItemQuery($this);
    $query->setItem($item);
    return $query;
  }

  /**
   * Create and return an instance of the UpdateItemQuery.
   *
   * @param string $id
   * @param array $item
   *
   * @return \EdgeBox\SyncCore\V1\Query\UpdateItemQuery
   */
  public function updateItem($id, $item) {
    $query = new UpdateItemQuery($this);
    $query->setEntityId($id);
    $query->setItem($item);
    return $query;
  }

  /**
   * Create and return an instance of the DeleteItemQuery.
   *
   * @param string $id
   *
   * @return \EdgeBox\SyncCore\V1\Query\DeleteItemQuery
   */
  public function deleteItem($id) {
    $query = new DeleteItemQuery($this);
    $query->setEntityId($id);
    return $query;
  }

}
