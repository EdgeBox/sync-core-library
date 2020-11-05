<?php

namespace SyncCore\V1\Configuration;

use Drupal\cms_content_sync\SyncCore\Interfaces\Configuration\IDefineFlow;
use Drupal\cms_content_sync\SyncCore\V1\BatchOperation;
use Drupal\cms_content_sync\SyncCore\V1\Storage\ObjectStorage;

/**
 *
 */
class DefineFlow extends BatchOperation implements IDefineFlow {

  /**
   * @var string
   */
  protected $machine_name;

  /**
   * DefineFlow constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\SyncCore $core
   * @param string $machine_name
   * @param string $name
   * @param string $config
   */
  public function __construct($core, $machine_name, $name, $config) {
    $app = $core->getApplication();

    parent::__construct(
      $core,
      ObjectStorage::ID,
      [
        'id' => ConfigurationService::OBJECT_STORAGE_TYPE . '-' . $app->getSiteId() . '-' . $machine_name,
        'type' => ConfigurationService::OBJECT_STORAGE_TYPE,
        'properties' => [
          'module_version' => $app->getApplicationModuleVersion(),
          'pools' => [],
          'id' => $machine_name,
          'name' => $name,
          'site' => $app->getSiteId(),
          'config' => $config,
        ],
      ]
    );

    $this->machine_name = $machine_name;
  }

  /**
   * @return string
   */
  public function getMachineName() {
    return $this->machine_name;
  }

  /**
   * @inheritdoc
   */
  public function usePool($pool_id) {
    $this->body['properties']['pools'][$pool_id] = TRUE;

    return new DefinePoolForFlow($this->core, $this, $pool_id);
  }

}
