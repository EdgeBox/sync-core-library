<?php

namespace EdgeBox\SyncCore\V1\Syndication;

use EdgeBox\SyncCore\Interfaces\Syndication\ITriggerPullSingle;
use EdgeBox\SyncCore\V1\Storage\ConnectionSynchronizationStorage;
use EdgeBox\SyncCore\V1\Storage\CustomStorage;

/**
 *
 */
class TriggerPullSingle implements ITriggerPullSingle {

  /**
   * @var \Drupal\cms_content_sync\SyncCore\V1\SyncCore
   */
  protected $core;

  /**
   * @var string
   */
  protected $type;

  /**
   * @var string
   */
  protected $bundle;

  /**
   * @var string
   */
  protected $entity_id;

  /**
   * @var string
   */
  protected $pool;

  /**
   * @var bool
   */
  protected $is_manual;

  /**
   * @var bool
   */
  protected $is_dependency;

  /**
   *
   */
  public function __construct($core, $type, $bundle, $entity_id) {
    $this->core = $core;
    $this->type = $type;
    $this->bundle = $bundle;
    $this->entity_id = $entity_id;
  }

  /**
   * @inheritdoc
   */
  public function fromPool($pool_id) {
    $this->pool = $pool_id;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function manually($set) {
    $this->is_manual = $set;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function asDependency($set) {
    $this->is_dependency = $set;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function execute() {
    $connection_id = CustomStorage::getCustomId(
      $this->pool,
      $this->core->getApplication()->getSiteMachineName(),
      $this->type,
      $this->bundle
    );

    $id = ConnectionSynchronizationStorage::getExternalConnectionSynchronizationId($connection_id, FALSE);

    $storage = $this
      ->core
      ->storage->getConnectionSynchronizationStorage();

    $sync = $storage->getEntity($id);

    $action = $sync->synchronizeSingle($this->entity_id);

    $action->isManual(!!$this->is_manual);
    $action->isDependency(!!$this->is_dependency);

    $action
      ->execute();

    return $this;
  }

  /**
   * @var array|null
   */
  protected $previewData = NULL;

  /**
   * @inheritdoc
   */
  public function getPullDashboardSearchResultItem() {
    $query = $this->core
      ->storage->getPreviewEntityStorage()
      ->getItem($this->entity_id)
      ->execute();

    $this->previewData = $query->getItem();

    return new PullDashboardSearchResultItem($this->previewData);
  }

}
