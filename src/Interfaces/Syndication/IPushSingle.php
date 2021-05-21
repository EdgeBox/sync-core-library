<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

use EdgeBox\SyncCore\Exception\SyncCoreException;

interface IPushSingle
{
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
     * @return $this
     */
    public function toPool(string $pool_id);

    /**
     * @return $this
     */
    public function asDependency(bool $set);

    /**
     * @return $this
     */
    public function delete(bool $set);

    /**
     * @param array      $pool_machine_names
     *                                       Can be any pool if it's not syndicated like Drupal config entities
     * @param array|null $details
     *                                       Any additional properties you may want to add
     *
     * @return array|object
     */
    // TODO: Drupal: Verify that getting an object here is acceptable.
    // TODO: Drupal: Provide multiple pools at once.
    public function addReference(string $type, string $bundle, string $uuid, ?string $id, string $version, array $pool_machine_names, string $language, $details = null);

    /**
     * @param array|null $details
     *                            Any additional properties you may want to add
     *
     * @return array|object
     */
    // TODO: Drupal: Verify that getting an object here is acceptable.
    // TODO: Drupal: Provide multiple pools at once.
    public function addDependency(string $type, string $bundle, string $uuid, ?string $id, string $version, array $pool_machine_names, string $language, $details = null);

    /**
     * @param IPushSingle $embed_entity
     *                                  The definition of the whole entity
     * @param array|null  $details
     *                                  Any additional properties you may want to add
     *
     * @return array|object
     */
    // TODO: Drupal: Verify that getting an object here is acceptable.
    public function embed(string $type, string $bundle, string $uuid, ?string $id, string $version, IPushSingle $embed_entity, $details = null);

    /**
     * @param mixed       $value
     * @param string|null $language
     *
     * @return $this
     */
    public function setProperty(string $name, $value, $language = null);

    /**
     * @param string|null $language
     *
     * @return $this
     */
    public function setName(string $value, $language = null);

    /**
     * @param string|null $language
     *
     * @return $this
     */
    public function setPreviewHtml(string $value, $language = null);

    /**
     * @param string|null $language
     *
     * @return $this
     */
    public function setSourceDeepLink(string $value, $language = null);

    /**
     * @param string|null $language
     *
     * @return mixed|null
     */
    public function getProperty(string $name, $language = null);

    /**
     * @return $this
     *
     * @throws SyncCoreException
     */
    public function execute();

    // TODO: Drupal: Provide $name.

    /**
     * @param string $content
     *                        The file content to store at the entity
     *
     * @return $this
     */
    public function uploadFile(string $content, ?string $name = null);
}
