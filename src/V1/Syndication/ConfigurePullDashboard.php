<?php

namespace SyncCore\V1\Syndication;

use Drupal\cms_content_sync\SyncCore\Interfaces\Syndication\IConfigurePullDashboard;
use Drupal\cms_content_sync\SyncCore\Interfaces\Syndication\IPullDashboardSearchResult;
use Drupal\cms_content_sync\SyncCore\V1\Entity\Entity;
use Drupal\cms_content_sync\SyncCore\V1\Query\Condition\DataCondition;
use Drupal\cms_content_sync\SyncCore\V1\Query\Condition\ParentCondition;
use Drupal\cms_content_sync\SyncCore\V1\Query\ListQuery;
use Drupal\cms_content_sync\SyncCore\V1\Storage\ApiStorage;
use Drupal\cms_content_sync\SyncCore\V1\Storage\EntityTypeStorage;
use Drupal\cms_content_sync\SyncCore\V1\Storage\PreviewEntityStorage;

/**
 *
 */
class ConfigurePullDashboard implements IConfigurePullDashboard {

  /**
   * @var \Drupal\cms_content_sync\SyncCore\V1\SyncCore
   */
  protected $core;

  /**
   * @var array
   */
  protected $config = [
    'api_version' => ApiStorage::CUSTOM_API_VERSION,
  ];

  /**
   * @var array
   */
  protected $filters = [];

  /**
   * @var string[]
   */
  protected $entity_type_ids = [];

  /**
   * ConfigurePullDashboard constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\SyncCore $core
   */
  public function __construct($core) {
    $this->core = $core;

    if ($this->core->isDirectUserAccessEnabled()) {
      $time = time();
      $authentication = $this->core->getApplication()->getAuthentication();
      $hash = md5($time . ':' . $authentication['password']);
      $token = base64_encode(
        json_encode([
          't' => $time,
          'h' => $hash,
          's' => $this->core->getApplication()->getSiteMachineName(),
        ])
      );
      $this->config['syncCoreUrl'] = $core->getBaseUrl(TRUE);
      $this->config['syncCoreToken'] = $token;
    }
  }

  /**
   * @inheritdoc
   */
  public function ifTaggedWith($pool_id, $type, $bundle, $property, $uuids) {
    $id = EntityTypeStorage::getExternalEntityTypeId(
      $pool_id,
      $type,
      $bundle
    );

    $this->filters[] = ParentCondition::any()
      ->add(
        ParentCondition::none()
          ->add(DataCondition::equal(PreviewEntityStorage::PROPERTY_ENTITY_TYPE_UNVERSIONED, $id))
      )
      ->add(DataCondition::in(PreviewEntityStorage::PROPERTY_CUSTOM_PROPERTIES . '.' . $property . '.' . Entity::UUID_KEY, $uuids));

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getConfig() {
    $config = $this->config;

    if (count($this->filters) == 1) {
      $config['syncCoreCondition'] = $this->filters[0]->serialize();
    }
    elseif (count($this->filters) > 1) {
      $config['syncCoreCondition'] = ParentCondition::all($this->filters)->serialize();
    }

    return $config;
  }

  /**
   * @inheritdoc
   */
  public function forEntityType($pool_id, $type, $bundle) {
    $id = EntityTypeStorage::getExternalEntityTypeId(
      $pool_id,
      $type,
      $bundle
    );

    $this->entity_type_ids[] = $id;

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function searchInTitle($text) {
    $this->filters[] = DataCondition::search(PreviewEntityStorage::PROPERTY_TITLE, $text);

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function searchInPreview($text) {
    $this->filters[] = DataCondition::search(PreviewEntityStorage::PROPERTY_PREVIEW_HTML, $text);

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function publishedBetween($from, $to) {
    if ($from) {
      $filters[] = DataCondition::greaterOrEqual(PreviewEntityStorage::PROPERTY_PUBLISHED_DATE, $from);
    }

    if ($to) {
      $filters[] = DataCondition::lessOrEqual(PreviewEntityStorage::PROPERTY_PUBLISHED_DATE, $to);
    }

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function runSearch($order_by_title = FALSE, $order_ascending = FALSE, $page = NULL) {
    $condition = DataCondition::in(PreviewEntityStorage::PROPERTY_ENTITY_TYPE_UNVERSIONED, $this->entity_type_ids);

    if (count($this->filters)) {
      $condition = ParentCondition::all(array_merge(
        $this->filters,
        [$condition]
      ));
    }

    $order_field = $order_by_title ? 'title' : 'published_date';

    $response = $this->core
      ->storage->getPreviewEntityStorage()
      ->listItems()
      ->setItemsPerPage(10)
      ->getDetails()
      ->setCondition($condition)
      ->orderBy($order_field,
        $order_ascending ?
          ListQuery::ORDER_ASCENDING :
          ListQuery::ORDER_DESCENDING
      )
      ->setPage($page ? $page : 1)
      ->execute()
      ->getRaw();

    return new class($response) implements IPullDashboardSearchResult {
      /**
       * @var array
       */
      protected $body;

      /**
       * constructor.
       *
       * @param array $body
       */
      public function __construct($body) {
        $this->body = $body;
      }

      /**
       * @inheritdoc
       */
      public function getItems() {
        $items = [];

        foreach ($this->body['items'] as &$item) {
          $items[] = new PullDashboardSearchResultItem($item);
        }

        return $items;
      }

      /**
       * @inheritdoc
       */
      public function toArray() {
        return $this->body;
      }

    };
  }

}
