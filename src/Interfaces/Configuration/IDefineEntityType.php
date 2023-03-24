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
}
