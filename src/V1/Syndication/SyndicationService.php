<?php

namespace SyncCore\V1\Syndication;

use Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException;
use Drupal\cms_content_sync\SyncCore\Interfaces\Syndication\ISyndicationService;
use Drupal\cms_content_sync\SyncCore\V1\Query\Condition\DataCondition;
use Drupal\cms_content_sync\SyncCore\V1\Query\Condition\ParentCondition;
use Drupal\cms_content_sync\SyncCore\V1\Query\SimpleQuery;
use Drupal\cms_content_sync\SyncCore\V1\Storage\CustomStorage;
use Drupal\cms_content_sync\SyncCore\V1\Storage\InstanceStorage;
use Drupal\cms_content_sync\SyncCore\V1\Storage\MetaInformationConnectionStorage;
use Drupal\cms_content_sync\SyncCore\V1\SyncCoreClient;

/**
 *
 */
class SyndicationService implements ISyndicationService {

  /**
   * @var \Drupal\cms_content_sync\SyncCore\V1\SyncCore
   */
  protected $core;

  /**
   * SyndicationService constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\SyncCore $core
   */
  public function __construct($core) {
    $this->core = $core;
  }

  /**
   * @inheritdoc
   */
  public function configurePullDashboard() {
    return new ConfigurePullDashboard($this->core);
  }

  /**
   * @inheritdoc
   */
  public function pullSingle($flow_id, $type, $bundle, $entity_id) {
    return new TriggerPullSingle($this->core, $type, $bundle, $entity_id);
  }

  /**
   * @inheritdoc
   */
  public function pullAll($flow_id, $type, $bundle) {
    return new PullAll($this->core, $type, $bundle);
  }

  /**
   * @inheritdoc
   */
  public function handlePull($flow_id, $type, $bundle, $data) {
    return new PullOperation($this->core, $type, $bundle, $data);
  }

  /**
   * @inheritdoc
   */
  public function pushSingle($flow_id, $type, $bundle, $entity_uuid, $entity_id) {
    return new PushSingle($this->core, $type, $bundle, $entity_uuid, $entity_id);
  }

  /**
   * @inheritdoc
   */
  public function getExternalUsages($pool_id, $entity_type, $bundle, $shared_entity_id) {
    /**
     * @var \Drupal\cms_content_sync\SyncCore\V1\Storage\MetaInformationConnectionStorage $storage
     */
    $storage = $this
      ->core
      ->storage->getMetaInformationConnectionStorage();

    $application = $this->core->getApplication();

    $items = $storage->listItems()
      ->orderBy('connection_id')
      ->setCondition(
        ParentCondition::all()
          ->add(DataCondition::equal(MetaInformationConnectionStorage::PROPERTY_ENTITY_ID, $shared_entity_id))
          ->add(DataCondition::startsWith(MetaInformationConnectionStorage::PROPERTY_CONNECTION_ID, $application->getApplicationId() . '-' . $pool_id . '-'))
          ->add(DataCondition::endsWith(MetaInformationConnectionStorage::PROPERTY_CONNECTION_ID, '-' . $entity_type . '-' . $bundle))
      )
      ->getDetails()
      ->execute()
      ->getAll();

    $result = [];

    foreach ($items as $item) {
      if (!empty($item['deleted_at'])) {
        continue;
      }
      $connection_id = !empty($item['connection']['id']) ? $item['connection']['id'] : $item['connection_id'];
      $site_id = preg_replace('@^' . $application->getApplicationId() . '-' . $pool_id . '-(.+)-' . $entity_type . '-' . $bundle . '$@', '$1', $connection_id);

      if ($site_id == InstanceStorage::POOL_SITE_ID) {
        continue;
      }

      if ($site_id == $application->getSiteMachineName()) {
        continue;
      }

      if (!empty($item['entity']['_resource_url'])) {
        $entity = SimpleQuery
          ::create($this->core, SyncCoreClient::getRelativeUrl($item['entity']['_resource_url']))
            ->execute()
            ->getResult();
      }
      else {
        $storage = new CustomStorage(
          $this->core,
          $pool_id,
          $site_id,
          $entity_type,
          $bundle
        );

        try {
          $entity = $storage
            ->getItem($shared_entity_id)
            ->execute()
            ->getItem();
        }
        catch (SyncCoreException $e) {
          continue;
        }
      }

      $result[$site_id] = $entity['url'];
    }

    return $result;
  }

  /**
   * Get a list of all Sync Core connections as resource URLs.
   *
   * @return \Drupal\cms_content_sync\SyncCore\V1\Entity\Connection[]
   *
   * @throws \Exception
   */
  protected function getConnectionEntities() {
    $storage = $this->core->storage->getConnectionStorage();
    $result = [];

    $items = $storage
      ->listItems()
      ->setCondition(DataCondition::equal('instance_id', $this->core->getApplication()->getSiteId()))
      ->execute()
      ->getAll();

    foreach ($items as $item) {
      $result[] = $storage->getEntity($item['id']);
    }

    return $result;
  }

  /**
   * @inheritdoc
   */
  public function refreshAuthentication() {
    $connections = $this->getConnectionEntities();

    $result = TRUE;

    foreach ($connections as $connection) {
      $result &= $connection
        ->login()
        ->execute()
        ->succeeded();
    }

    return $result;
  }

}
