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
     * Define whether the entities should be processed one after
     * another rather than in parallel. Keep in mind that this opens the door
     * to cascading update failures, so it's not preferred. Should only be used
     * for important publishing events where the order of updates has a
     * significant impact and outweighs the risks.
     *
     * @return $this
     */
    public function runInOrder(bool $set);

    /**
     * Define whether this is a priority publication.
     *
     * @param int $priority see ISyncCore::PRIORITY_* constants
     *
     * @return $this
     */
    public function setPriority(int $priority);

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
