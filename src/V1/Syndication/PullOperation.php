<?php

namespace EdgeBox\SyncCore\V1\Syndication;

use EdgeBox\SyncCoreInterfaces\Syndication\IEntityReference;
use EdgeBox\SyncCoreInterfaces\Syndication\IPullOperation;
use EdgeBox\SyncCoreV1\Entity\Entity;

/**
 *
 */
class PullOperation implements IPullOperation {

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
   * @var array
   */
  protected $body;

  /**
   * PushSingle constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\SyncCore $core
   * @param string $type
   * @param string $bundle
   * @param array $body
   */
  public function __construct($core, $type, $bundle, $body) {
    $this->core = $core;
    $this->type = $type;
    $this->bundle = $bundle;
    $this->body = $body;
  }

  /**
   * @inheritdoc
   */
  public function getId() {
    return $this->body['id'];
  }

  /**
   * @inheritdoc
   */
  public function getUuid() {
    return $this->body['uuid'];
  }

  /**
   * @inheritdoc
   */
  public function getSourceUrl() {
    return isset($this->body['url']) ? $this->body['url'] : '';
  }

  /**
   * @inheritdoc
   */
  public function getUsedTranslationLanguages() {
    return empty($this->body['apiu_translation']) ? [] : array_keys($this->body['apiu_translation']);
  }

  /**
   * @inheritdoc
   */
  public function getProperty($name, $language = NULL) {
    if ($language) {
      if (!isset($this->body['apiu_translation'][$language][$name])) {
        return NULL;
      }
      return $this->body['apiu_translation'][$language][$name];
    }

    if (!isset($this->body[$name])) {
      return NULL;
    }
    return $this->body[$name];
  }

  /**
   * @inheritdoc
   */
  public function loadReference($definition) {
    return new class ($this->core, $definition) implements IEntityReference {

      /**
       * @var \Drupal\cms_content_sync\SyncCore\V1\SyncCore
       */
      protected $core;

      /**
       * @var array
       */
      protected $definition;

      /**
       * constructor.
       *
       * @param \Drupal\cms_content_sync\SyncCore\V1\SyncCore $core
       * @param array $definition
       */
      public function __construct($core, $definition) {
        $this->core = $core;
        $this->definition = $definition;
      }

      /**
       * @inheritdoc
       */
      public function getDetails() {
        return array_filter($this->definition, function ($key) {
          return !in_array($key, [
            Entity::API_KEY,
            Entity::ENTITY_TYPE_KEY,
            Entity::BUNDLE_KEY,
            Entity::VERSION_KEY,
            Entity::UUID_KEY,
            Entity::ID_KEY,
            Entity::AUTO_PUSH_KEY,
            Entity::SOURCE_CONNECTION_ID_KEY,
            Entity::POOL_CONNECTION_ID_KEY,
            Entity::ENTITY_EMBED_KEY,
            Entity::ENTITY_HASH_KEY,
          ]);
        }, ARRAY_FILTER_USE_KEY);
      }

      /**
       * @inheritdoc
       */
      public function getId() {
        return empty($this->definition[Entity::ID_KEY]) ? NULL : $this->definition[Entity::ID_KEY];
      }

      /**
       * @inheritdoc
       */
      public function getUuid() {
        return empty($this->definition[Entity::UUID_KEY]) ? NULL : $this->definition[Entity::UUID_KEY];
      }

      /**
       * @inheritdoc
       */
      public function getType() {
        return empty($this->definition[Entity::ENTITY_TYPE_KEY]) ? NULL : $this->definition[Entity::ENTITY_TYPE_KEY];
      }

      /**
       * @inheritdoc
       */
      public function getBundle() {
        return empty($this->definition[Entity::BUNDLE_KEY]) ? NULL : $this->definition[Entity::BUNDLE_KEY];
      }

      /**
       * @inheritdoc
       */
      public function getVersion() {
        return empty($this->definition[Entity::VERSION_KEY]) ? NULL : $this->definition[Entity::VERSION_KEY];
      }

      /**
       * @inheritdoc
       */
      public function getName() {
        return empty($this->definition[Entity::LABEL_KEY]) ? NULL : $this->definition[Entity::LABEL_KEY];
      }

      /**
       * @inheritdoc
       */
      public function getPoolId() {
        return empty($this->definition[Entity::API_KEY]) ? NULL : $this->definition[Entity::API_KEY];
      }

      /**
       * @inheritdoc
       */
      public function isEmbedded() {
        return $this->definition[Entity::AUTO_PUSH_KEY] === Entity::ENTITY_REFERENCE_EMBED;
      }

      /**
       * @inheritdoc
       */
      public function getEmbeddedEntity() {
        return new PullOperation($this->core, $this->getType(), $this->getBundle(), $this->definition[Entity::ENTITY_EMBED_KEY]);
      }

    };
  }

  /**
   * @inheritdoc
   */
  public function downloadFile() {
    $content = $this->getProperty('apiu_file_content');
    if ($content) {
      $content = base64_decode($content);
    }
    return $content;
  }

  /**
   * @inheritdoc
   */
  public function getName($language = NULL) {
    return $this->getProperty(Entity::PROPERTY_NAME, $language);
  }

  /**
   * @inheritdoc
   */
  public function getResponseBody($entity_deep_link) {
    $data = $this->body;
    $data['url'] = $entity_deep_link;
    return $data;
  }

}
