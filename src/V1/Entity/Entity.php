<?php

namespace EdgeBox\SyncCore\V1\Entity;

/**
 *
 */
class Entity {

  const PROPERTY_NAME = 'title';

  const PROPERTY_PREVIEW_HTML = 'preview';

  const PROPERTY_SOURCE_DEEP_LINK_URL = 'url';

  /**
   * Keys used in the definition array for embedded entities.
   *
   * @var string ENTITY_HASH_KEY          A unique hash of the serialized
   *   entity.
   * @var string API_KEY                  The API of the processed and
   *   referenced entity.
   * @var string ENTITY_TYPE_KEY          The entity type of the referenced
   *   entity.
   * @var string BUNDLE_KEY               The bundle of the referenced entity.
   * @var string VERSION_KEY              The version of the entity type of the
   *   referenced entity.
   * @var string UUID_KEY                 The UUID of the referenced entity.
   * @var string AUTO_PUSH_KEY            Whether or not to automatically push
   *   the referenced entity as well.
   * @var string SOURCE_CONNECTION_ID_KEY The Sync Core connection ID of the
   *   referenced entity.
   * @var string POOL_CONNECTION_ID_KEY   The Sync Core connection ID of the
   *   pool for this api + entity type + bundle.
   * @see PushIntent::embed        for its usage on push. (Drupal)
   * @see PushIntent::addDependency        for its usage on push. (Drupal)
   * @see PushIntent::addReference        for its usage on push. (Drupal)
   * @see PullIntent::loadEmbeddedEntity for its usage on pull. (Drupal)
   *
   */
  const ENTITY_HASH_KEY = 'entity_hash';

  const API_KEY = 'api';

  const BUNDLE_KEY = 'bundle';

  const LABEL_KEY = 'label';

  const ID_KEY = 'id';

  const ENTITY_TYPE_KEY = 'type';

  const VERSION_KEY = 'version';

  const AUTO_PUSH_KEY = 'auto_export';

  const POOL_CONNECTION_ID_KEY = 'next_connection_id';

  const UUID_KEY = 'uuid';

  const SOURCE_CONNECTION_ID_KEY = 'connection_id';

  const ENTITY_EMBED_KEY = 'entity';

  /**
   * @var int ENTITY_REFERENCE_PUSH_AS_DEPENDENCY
   *   Push the referenced entity in parallel to the current entity. The Sync
   *   Core will then pull the referenced entity before this entity to make
   *   sure the reference can be resolved when this field is pulled.
   */
  const ENTITY_REFERENCE_PUSH_AS_DEPENDENCY = 1;

  /**
   * @var int ENTITY_REFERENCE_EMBED
   *   Embed the full entity within this field, so no other request is required.
   *   The remote site will then use the definition to pull the entity when
   *   the field is pulled.
   */
  const ENTITY_REFERENCE_EMBED = 2;

  /**
   * @var int ENTITY_REFERENCE_RESOLVE_IF_EXISTS
   *   Don't push the referenced entity automatically, but if the entity
   *   exists on the remote site, resolve the reference on the field when
   *   pulling this entity.
   */
  const ENTITY_REFERENCE_RESOLVE_IF_EXISTS = 0;

  /**
   * @var \EdgeBox\SyncCore\V1\Storage\Storage
   */
  protected $storage;

  /**
   * @var string
   */
  protected $id;

  /**
   * Entity constructor.
   *
   * @param \EdgeBox\SyncCore\V1\Storage\Storage $storage
   * @param string $id
   */
  public function __construct($storage, $id) {
    $this->storage = $storage;
    $this->id = $id;
  }

  /**
   * @return \EdgeBox\SyncCore\V1\Storage\Storage
   */
  public function getStorage() {
    return $this->storage;
  }

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

}
