<?php

namespace EdgeBox\SyncCore\V2;

use EdgeBox\SyncCore\Interfaces\IBatch;

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
     * {@inheritdoc}
     */
    public function add($operation)
    {
        /**
         * @var BatchOperation $operation
         */
        $this->operations[] = $operation;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->operations);
    }

    /**
     * {@inheritdoc}
     */
    public function get($index)
    {
        return $this->operations[$index];
    }

    /**
     * {@inheritdoc}
     */
    public function executeAll()
    {
        foreach ($this->operations as $operation) {
            $operation->execute();
        }
    }

    /**
     * @param Batch $other
     */
    public function prepend($other)
    {
        $this->operations = array_merge(
      $other->getOperations(),
      $this->operations
    );
    }

    /**
     * @return BatchOperation[]
     */
    public function getOperations()
    {
        return $this->operations;
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
}
