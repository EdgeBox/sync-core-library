<?php

namespace EdgeBox\SyncCore\V1\Storage;

use EdgeBox\SyncCore\V1\SyncCore;

/**
 * Class CustomStorage
 * Implement Storage for any custom entity type + pool + site combination that
 * has been exported before through configuration.
 *
 * @package Drupal\cms_content_sync\SyncCore\V1\Storage
 */
class CustomStorage extends Storage {

  const APPLICATION_DRUPAL = 'drupal';

  /**
   * @var string POOL_DEPENDENCY_CONNECTION_ID
   *   Same as {@see Flow::DEPENDENCY_CONNECTION_ID} but for the
   *   pool connection.
   */
  const POOL_DEPENDENCY_CONNECTION_ID = 'drupal-[api.name]-' . InstanceStorage::POOL_SITE_ID . '-[entity_type.name_space]-[entity_type.name]';

  /**
   * @var string DEPENDENCY_CONNECTION_ID
   *   The format for connection IDs. Must be used consequently to allow
   *   references to be resolved correctly.
   */
  const DEPENDENCY_CONNECTION_ID = 'drupal-[api.name]-[instance.id]-[entity_type.name_space]-[entity_type.name]';

  /**
   * @var string
   */
  protected $apiId;

  /**
   * @var string
   */
  protected $siteId;

  /**
   * @var string
   */
  protected $entityTypeName;

  /**
   * @var string
   */
  protected $bundleName;

  /**
   * CustomStorage constructor.
   *
   * @param \EdgeBox\SyncCore\V1\SyncCore $client
   * @param string $api_id
   * @param string $site_id
   * @param string $entity_type_name
   * @param string $bundle_name
   */
  public function __construct(SyncCore $client, $api_id, $site_id, $entity_type_name, $bundle_name) {
    parent::__construct($client);

    $this->apiId = $api_id;
    $this->siteId = $site_id;
    $this->entityTypeName = $entity_type_name;
    $this->bundleName = $bundle_name;
  }

  /**
   * @inheritdoc
   */
  public function getId() {
    return self::getCustomId($this->apiId, $this->siteId, $this->entityTypeName, $this->bundleName);
  }

  /**
   * Get the Sync Core connection ID for the given entity type config.
   *
   * @param string $api_id
   *   API ID from this config.
   * @param string $site_id
   *   ID from this site from this config.
   * @param string $entity_type_name
   *   The entity type.
   * @param string $bundle_name
   *   The bundle.
   *
   * @return string A unique connection ID.
   */
  public static function getCustomId($api_id, $site_id, $entity_type_name, $bundle_name) {
    return sprintf('drupal-%s-%s-%s-%s',
      $api_id,
      $site_id,
      $entity_type_name,
      $bundle_name
    );
  }

  /**
   * @inheritdoc
   */
  public function getPath() {
    return '/' . self::getCustomPath($this->apiId, $this->siteId, $this->entityTypeName, $this->bundleName);
  }

  /**
   * Get the Sync Core connection path for the given entity type config.
   *
   * @param string $api_id
   *   API ID from this config.
   * @param string $site_id
   *   ID from this site from this config.
   * @param string $entity_type_name
   *   The entity type.
   * @param string $bundle_name
   *   The bundle.
   *
   * @return string A unique connection path.
   */
  public static function getCustomPath($api_id, $site_id, $entity_type_name, $bundle_name) {
    return sprintf('drupal/%s/%s/%s/%s',
      $api_id,
      $site_id,
      $entity_type_name,
      $bundle_name
    );
  }

}
