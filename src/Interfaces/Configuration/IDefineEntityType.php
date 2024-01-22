<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

interface IDefineEntityType extends IBatchOperation, IDefineObject
{
    /**
     * @return $this
     */
    public function isTranslatable(bool $set);

    /**
     * @return $this
     */
    public function isFile(bool $set);

    /**
     * Provide an optional description for the entity type.
     *
     * @return $this
     */
    public function setDescription(string $description);
}
