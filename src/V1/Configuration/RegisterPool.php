<?php

namespace EdgeBox\SyncCore\V1\Configuration;

use Drupal\cms_content_sync\SyncCore\Interfaces\Configuration\IRegisterPool;
use Drupal\cms_content_sync\SyncCore\V1\BatchOperation;
use Drupal\cms_content_sync\SyncCore\V1\Storage\ApiStorage;

/**
 *
 */
class RegisterPool extends BatchOperation implements IRegisterPool {

  /**
   * @var \Drupal\cms_content_sync\SyncCore\V1\SyncCore
   */
  protected $core;

  /**
   * @var string
   */
  protected $pool_id;

  /**
   * @var string
   */
  protected $pool_name;

  /**
   * RegisterPool constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\SyncCore $core
   * @param string $pool_id
   * @param string $pool_name
   */
  public function __construct($core, $pool_id, $pool_name) {
    parent::__construct(
      $core,
      ApiStorage::ID,
      [
        'id' => $pool_id . '-' . ApiStorage::CUSTOM_API_VERSION,
        'name' => $pool_name,
        'version' => ApiStorage::CUSTOM_API_VERSION,
        'parent_id' => 'drupal-' . ApiStorage::CUSTOM_API_VERSION,
      ]
    );
  }

}
