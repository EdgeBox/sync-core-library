<?php

namespace EdgeBox\SyncCore\V1\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefinePoolForFlow;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V1\BatchOperation;
use EdgeBox\SyncCore\V1\Storage\ConnectionSynchronizationStorage;
use EdgeBox\SyncCore\V1\Storage\CustomStorage;
use EdgeBox\SyncCore\V1\Storage\EntityTypeStorage;
use EdgeBox\SyncCore\V1\Storage\InstanceStorage;
use EdgeBox\SyncCore\V1\Storage\PreviewEntityStorage;
use EdgeBox\SyncCore\V1\Storage\RemoteStorageStorage;

class DefinePoolForFlow extends BatchOperation implements IDefinePoolForFlow
{
    /**
     * @var string
     */
    protected $pool_id;

    /**
     * @var DefineFlow
     */
    protected $flow;

    /**
     * DefineFlow constructor.
     *
     * @param \EdgeBox\SyncCore\V1\SyncCore $core
     * @param DefineFlow                    $flow
     * @param string                        $pool_id
     */
    public function __construct($core, $flow, $pool_id)
    {
        $app = $core->getApplication();

        $authentication = $app->getAuthentication();
        if (IApplicationInterface::AUTHENTICATION_TYPE_COOKIE === $authentication['type']) {
            $authentication['type'] = 'drupal8_services';
        } else {
            $authentication['type'] = 'basic_auth';
        }
        $authentication['base_url'] = $app->getSiteBaseUrl();

        parent::__construct(
      $core,
      RemoteStorageStorage::ID,
      [
        'id' => RemoteStorageStorage::getStorageId($pool_id, $app->getSiteId()),
        'name' => 'Drupal connection on '.$app->getSiteId().' for '.$pool_id,
        'status' => 'READY',
        'instance_id' => $app->getSiteId(),
        'api_id' => $pool_id,
        'entity_type_ids' => [],
        'connection_id_pattern' => CustomStorage::getCustomId('[api.id]', '[instance.id]', '[entity_type.name_space]', '[entity_type.name]'),
        'connection_name_pattern' => 'Drupal connection on [instance.id] for [entity_type.name_space].[entity_type.name]:[entity_type.version]',
        'connection_path_pattern' => CustomStorage::getCustomPath('[api.id]', '[instance.id]', '[entity_type.name_space]', '[entity_type.name]'),
        'connection_options' => [
          'list_url' => $app->getRestUrl('[api.id]', '[entity_type.name_space]', '[entity_type.name]', '[entity_type.version]', null, '[is_manual]', '[is_dependency]'),
          'item_url' => $app->getRestUrl('[api.id]', '[entity_type.name_space]', '[entity_type.name]', '[entity_type.version]', '[id]', '[is_manual]', '[is_dependency]'),
          'authentication' => $authentication,
          // $cms_content_sync_disable_optimization,
          'update_all' => false,
        ],
      ]
    );

        $this->flow = $flow;
        $this->pool_id = $pool_id;
    }

    /**
     * @return string
     */
    public function getPoolId()
    {
        return $this->pool_id;
    }

    /**
     * @return DefineFlow
     */
    public function getFlow()
    {
        return $this->flow;
    }

    /**
     * @param DefineEntityType $type
     *
     * @return $this
     */
    public function enablePreview($type)
    {
        $pool_connection_id = CustomStorage::getCustomId(
      $this->pool_id,
      InstanceStorage::POOL_SITE_ID,
      $type->getTypeMachineName(),
      $type->getBundleMachineName()
    );

        $this->addDownstream(
      ConnectionSynchronizationStorage::ID,
      [
        'id' => $pool_connection_id.'--to--preview',
        'name' => 'Synchronization Pool '.$type->getTypeMachineName().'-'.$type->getBundleMachineName().' -> Preview',
        'options' => [
          'create_entities' => true,
          'update_entities' => true,
          'delete_entities' => true,
          'update_none_when_loading' => true,
          'exclude_reference_properties' => [
            'pSource',
          ],
        ],
        'status' => 'READY',
        'source_connection_id' => $pool_connection_id,
        'destination_connection_id' => PreviewEntityStorage::ID,
      ]
    );

        return $this;
    }

    /**
     * @param DefineEntityType $type
     *
     * @return $this
     */
    public function useEntityType($type)
    {
        $entity_type_id = EntityTypeStorage::getExternalEntityTypeId($this->pool_id, $type->getTypeMachineName(), $type->getBundleMachineName(), $type->getVersionId());

        if (!in_array($entity_type_id, $this->body['entity_type_ids'])) {
            $this->body['entity_type_ids'][] = $entity_type_id;
        }

        return $this;
    }

    /**
     * @param DefineEntityType $type
     *
     * @return $this
     */
    public function enablePush($type)
    {
        $app = $this->core->getApplication();

        $local_connection_id = CustomStorage::getCustomId($this->pool_id, $app->getSiteId(), $type->getTypeMachineName(), $type->getBundleMachineName());

        $pool_connection_id = CustomStorage::getCustomId($this->pool_id, InstanceStorage::POOL_SITE_ID, $type->getTypeMachineName(), $type->getBundleMachineName());

        $this->addDownstream(
      ConnectionSynchronizationStorage::ID,
      [
        'id' => ConnectionSynchronizationStorage::getExternalConnectionSynchronizationId($local_connection_id, true),
        'name' => 'Synchronization for '.$type->getTypeMachineName().'/'.$type->getBundleMachineName().'/'.$type->getVersionId().' from '.$app->getSiteId().' -> Pool',
        'options' => [
          'dependency_connection_id' => CustomStorage::POOL_DEPENDENCY_CONNECTION_ID,
          // As entities will only be sent to Sync Core if the sync config
          // allows it, the synchronization entity doesn't need to filter
          // any further.
          'create_entities' => true,
          'update_entities' => true,
          'delete_entities' => true,
          // $cms_content_sync_disable_optimization,
          'force_updates' => false,
          'dependent_entities_only' => false,
          'update_none_when_loading' => true,
          'exclude_reference_properties' => [
            'pSource',
          ],
        ],
        'status' => 'READY',
        'source_connection_id' => $local_connection_id,
        'destination_connection_id' => $pool_connection_id,
      ]
    );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function enablePull($type)
    {
        /*
         * @var DefineEntityType $type
         */
        return new FlowPullConfiguration($this->core, $this, $type);
    }
}
