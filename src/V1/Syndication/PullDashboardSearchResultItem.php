<?php

namespace EdgeBox\SyncCore\V1\Syndication;

use EdgeBox\SyncCore\Interfaces\Syndication\IPullDashboardSearchResultItem;

/**
 *
 */
class PullDashboardSearchResultItem implements IPullDashboardSearchResultItem {

  /**
   * @var array
   */
  protected $item;

  /**
   * Constructor.
   *
   * @param array $item
   */
  public function __construct(&$item) {
    $this->item = &$item;
  }

  /**
   * @inheritdoc
   */
  public function getId() {
    return $this->item['id'];
  }

  /**
   * @inheritdoc
   */
  public function getType() {
    $entity_type_id = $this->item['entity_type_id'];
    list(, , $entity_type_name,) = explode('-', $entity_type_id);

    return $entity_type_name;
  }

  /**
   * @inheritdoc
   */
  public function getBundle() {
    $entity_type_id = $this->item['entity_type_id'];
    list(, , , $bundle_name) = explode('-', $entity_type_id);

    return $bundle_name;
  }

  /**
   * @inheritdoc
   */
  public function extend($properties) {
    foreach ($properties as $key => $value) {
      $this->item[$key] = $value;
    }
    return $this->item;
  }

  /**
   * @inheritdoc
   */
  public function toArray() {
    return $this->item;
  }

}
