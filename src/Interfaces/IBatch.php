<?php

namespace EdgeBox\SyncCore\Interfaces;

use EdgeBox\SyncCore\Exception\SyncCoreException;

interface IBatch
{
    /**
     * @return $this
     */
    public function add(IBatchOperation $operation);

    /**
     * @return int
     */
    public function count();

    /**
     * @return IBatchOperation
     */
    public function get(int $index);

    /**
     * Convenience method to call ->execute() for all indices until ->count().
     *
     * @throws SyncCoreException
     */
    public function executeAll();

    /**
     * Can only be used for batch operations using the same Sync Core!
     */
    public function prepend(IBatch $other);
}
