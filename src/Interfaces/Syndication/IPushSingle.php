<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

/**
 *
 */
interface IPushSingle {

  /**
   * Get a unique hash for the serialized entity. Exact implementation may
   * depend on the Sync Core version, so the entity hash may change between
   * updates of the module as well. This is intended as it allows the Sync
   * Core to request entities to be pushed again.
   * IMPORTANT: The hash should only be requested *after all properties were set
   * and the entity is completely serialized*. Otherwise it will create an
   * unreliable hash of the partial data available. As the hash is cached,
   * this will render all subsequent requests for the same entity hash unusable
   * as well!
   *
   * @return string
   */
  public function getEntityHash();

  /**
   * @param string $pool_id
   *
   * @return $this
   */
  public function toPool($pool_id);

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function asDependency($set);

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function delete($set);

  /**
   * @param string $type
   * @param string $bundle
   * @param string $uuid
   * @param string|null $id
   * @param string $version
   * @param string $pool_id
   *   Can be any pool if it's not syndicated like Drupal config entities.
   * @param array|null $details
   *   Any additional properties you may want to add.
   *
   * @return array
   */
  public function addReference($type, $bundle, $uuid, $id, $version, $pool_id, $details = NULL);

  /**
   * @param string $type
   * @param string $bundle
   * @param string $uuid
   * @param string|null $id
   * @param string $version
   * @param string $pool_id
   * @param array|null $details
   *   Any additional properties you may want to add.
   *
   * @return array
   */
  public function addDependency($type, $bundle, $uuid, $id, $version, $pool_id, $details = NULL);

  /**
   * @param string $type
   * @param string $bundle
   * @param string $uuid
   * @param string|null $id
   * @param string $version
   * @param IPushSingle $embed_entity
   *   The definition of the whole entity.
   * @param array|null $details
   *   Any additional properties you may want to add.
   *
   * @return array
   */
  public function embed($type, $bundle, $uuid, $id, $version, $embed_entity, $details = NULL);

  /**
   * @param string $name
   * @param mixed $value
   * @param string|null $language
   *
   * @return $this
   */
  public function setProperty($name, $value, $language = NULL);

  /**
   * @param string $value
   * @param string|null $language
   *
   * @return $this
   */
  public function setName($value, $language = NULL);

  /**
   * @param string $value
   * @param string|null $language
   *
   * @return $this
   */
  public function setPreviewHtml($value, $language = NULL);

  /**
   * @param string $value
   * @param string|null $language
   *
   * @return $this
   */
  public function setSourceDeepLink($value, $language = NULL);

  /**
   * @param string $name
   * @param string|null $language
   *
   * @return mixed|null
   */
  public function getProperty($name, $language = NULL);

  /**
   * @return $this
   *
   * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
   */
  public function execute();

  /**
   * @param string $content
   *   The file content to store at the entity.
   *
   * @return $this
   */
  public function uploadFile($content);

}
