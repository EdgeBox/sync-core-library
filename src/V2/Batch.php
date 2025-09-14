<?php

namespace EdgeBox\SyncCore\V2;

use EdgeBox\SyncCore\Interfaces\IBatch;
use EdgeBox\SyncCore\Interfaces\IBatchOperation;

class Batch implements IBatch
{
    /**
     * @var BatchOperation[]
     */
    protected $operations = [];

    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * Batch constructor.
     */
    public function __construct(SyncCore $core)
    {
        $this->core = $core;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return [
            'operations',
        ];
    }

    public function add(IBatchOperation $operation)
    {
        /**
         * @var BatchOperation $operation
         */
        $this->operations[] = $operation;

        return $this;
    }

    public function count()
    {
        return count($this->operations);
    }

    public function get(int $index)
    {
        return $this->operations[$index];
    }

    public function executeAll()
    {
        foreach ($this->operations as $operation) {
            $operation->execute();
        }
    }

    public function prepend(IBatch $other)
    {
        /**
         * @var Batch $other
         */
        $this->operations = array_merge(
            $other->getOperations(),
            $this->operations
        );
    }

    public function prependOperation(IBatchOperation $operation)
    {
        /**
         * @var BatchOperation $operation
         */
        $this->operations = array_merge(
            [$operation],
            $this->operations
        );

        return $this;
    }

    /**
     * @return BatchOperation[]
     */
    public function getOperations()
    {
        return $this->operations;
    }
}
