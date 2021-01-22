<?php

namespace EdgeBox\SyncCore\V1\Configuration;

use EdgeBox\SyncCore\V1\Storage\ConnectionSynchronizationStorage;
use EdgeBox\SyncCore\V1\Storage\CustomStorage;
use EdgeBox\SyncCore\V1\Storage\InstanceStorage;

/**
 *
 */
class FlowPullConfiguration extends FlowPullConfigurationBase {

  /**
   * DefineFlow constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\SyncCore $core
   * @param DefinePoolForFlow $pool
   * @param DefineEntityType $type
   */
  public function __construct($core, $pool, $type) {
    $app = $core->getApplication();

    $local_connection_id = CustomStorage::getCustomId(
      $pool->getPoolId(),
      $app->getSiteId(),
      $type->getTypeMachineName(),
      $type->getBundleMachineName()
    );

    $pool_connection_id = CustomStorage::getCustomId(
      $pool->getPoolId(),
      InstanceStorage::POOL_SITE_ID,
      $type->getTypeMachineName(),
      $type->getBundleMachineName()
    );

    $flow = $pool->getFlow();

    parent::__construct(
      $core,
      ConnectionSynchronizationStorage::ID,
      [
        'id' => ConnectionSynchronizationStorage::getExternalConnectionSynchronizationId($local_connection_id, FALSE),
        'name' => 'Synchronization for ' . $type->getTypeMachineName() . '/' . $type->getBundleMachineName() . '/' . $type->getVersionId() . ' from Pool -> ' . $app->getSiteId(),
        'options' => [
          'dependency_connection_id' => CustomStorage::DEPENDENCY_CONNECTION_ID,
          'modes' => [
            [
              'id' => $flow->getMachineName(),
              // $type['import'] == PullIntent::PULL_MANUALLY,
              'is_manual' => FALSE,
              // $type['import'] == PullIntent::PULL_AS_DEPENDENCY,
              'is_dependent' => FALSE,
              // $pull_condition ? $pull_condition->toArray() : NULL,
              'condition' => NULL,
              // boolval($type['import_deletion_settings']['import_deletion']),.
              'delete' => FALSE,
            ],
          ],
          // $cms_content_sync_disable_optimization,
          'force_updates' => FALSE,
          'create_entities' => TRUE,
          'update_entities' => TRUE,
          'delete_entities' => TRUE,
          'update_none_when_loading' => TRUE,
          'exclude_reference_properties' => [
            'pSource',
          ],
        ],
        'status' => 'READY',
        'source_connection_id' => $pool_connection_id,
        'destination_connection_id' => $local_connection_id,
      ]
    );
  }

  /**
   * @inheritdoc
   */
  public function configureOverride($flow_id) {
    return new class($this->core, $this->type, $this->body, $flow_id) extends FlowPullConfigurationBase {

      /**
       *
       */
      public function __construct($core, $type, &$body, $flow_id) {
        parent::__construct($core, $type, []);

        $this->body = &$body;
        $this->index = count($this->body['options']['modes']);
        $this->body['options']['modes'][] = [
          'id' => $flow_id,
          'is_manual' => FALSE,
          'is_dependent' => FALSE,
          'condition' => NULL,
          'delete' => FALSE,
        ];
      }

      /**
       *
       */
      public function configureOverride($flow_id) {
        return NULL;
      }

    };
  }

}
