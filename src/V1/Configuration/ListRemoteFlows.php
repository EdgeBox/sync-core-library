<?php

namespace EdgeBox\SyncCore\V1\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IListRemoteFlows;
use EdgeBox\SyncCore\Interfaces\Configuration\IRemoteFlowListItem;
use EdgeBox\SyncCore\V1\Query\Condition\DataCondition;
use EdgeBox\SyncCore\V1\Storage\ObjectStorage;

/**
 *
 */
class ListRemoteFlows implements IListRemoteFlows {

  /**
   * @var \EdgeBox\SyncCore\V1\SyncCore
   */
  protected $core;

  /**
   * @var string
   */
  protected $remote_module_version;

  /**
   * @var string[]
   */
  protected $pools = [];

  /**
   *
   */
  public function __construct($core, $remote_module_version) {
    $this->core = $core;
    $this->remote_module_version = $remote_module_version;
  }

  /**
   * @inheritdoc
   */
  public function thatUsePool($pool_id) {
    $this->pools[] = $pool_id;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function execute() {
    $object_storage = new ObjectStorage($this->core);
    $conditions = [];
    $conditions[] = DataCondition::equal(ObjectStorage::PROPERTY_TYPE, ConfigurationService::OBJECT_STORAGE_TYPE);
    $conditions[] = DataCondition::equal(ConfigurationService::OBJECT_STORAGE_PROPERTY_MODULE_VERSION, $this->remote_module_version);

    foreach ($this->pools as $pool) {
      $conditions[] = DataCondition::equal(ConfigurationService::OBJECT_STORAGE_PROPERTY_POOLS . '.' . $pool, TRUE);
    }

    $items = $object_storage
      ->listItems()
      ->setCondition($conditions)
      ->getDetails()
      ->execute()
      ->getAll();

    $result = [];

    foreach ($items as $item) {
      $result[] = new class($item) implements IRemoteFlowListItem {

        protected $item;

        /**
         *
         */
        public function __construct($item) {
          $this->item = $item;
        }

        /**
         *
         */
        public function getId() {
          return $this->item['id'];
        }

        /**
         *
         */
        public function getName() {
          return $this->item['properties']['name'];
        }

        /**
         *
         */
        public function getSiteName() {
          return $this->item['properties']['site'];
        }

      };
    }

    return $result;
  }

}
