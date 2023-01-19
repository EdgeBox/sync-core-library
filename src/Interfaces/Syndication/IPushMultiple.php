<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

use EdgeBox\SyncCore\Exception\SyncCoreException;

interface IPushMultiple
{
    /**
     * Add an entity to push.
     *
     * @return IPushMultipleItem
     */
    public function addEntity(string $type, string $bundle, string $version_id, string $root_language, ?string $entity_uuid, ?string $entity_id);

    /**
     * @throws SyncCoreException
     *
     * @return $this
     */
    public function execute();

    /**
     * Get the serialized entity as it is sent to the Sync Core. Only needed for
     * debugging purpooses.
     *
     * @return mixed
     */
    public function getData();
}
