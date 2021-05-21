<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefineEntityType;
use EdgeBox\SyncCore\Interfaces\Configuration\IDefinePoolForFlow;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\SyncCore;

class DefinePoolForFlow extends BatchOperation implements IDefinePoolForFlow
{
    /**
     * @var string
     */
    protected $poolMachineName;

    /**
     * @var DefineFlow
     */
    protected $flow;

    /**
     * DefineFlow constructor.
     */
    public function __construct(SyncCore $core, DefineFlow $flow, string $pool_machine_name)
    {
        parent::__construct(
      $core,
      null,
      null
    );

        $this->flow = $flow;
        $this->poolMachineName = $pool_machine_name;
    }

    /**
     * @return string
     */
    public function getPoolMachineName()
    {
        return $this->poolMachineName;
    }

    /**
     * @return DefineFlow
     */
    public function getFlow()
    {
        return $this->flow;
    }

    /**
     * @return $this
     */
    public function enablePreview(IDefineEntityType $entity_type)
    {
        // Nothing to do in V2.
        return $this;
    }

    /**
     * @return $this
     */
    public function useEntityType(IDefineEntityType $entity_type)
    {
        // Nothing to do in V2.
        return $this;
    }

    /**
     * @return $this
     */
    public function enablePush(IDefineEntityType $entity_type)
    {
        /**
         * @var DefineEntityType $entity_type
         */
        $this->flow->enablePush($entity_type, $this->poolMachineName);

        return $this;
    }

    /**
     * @return FlowPullConfiguration
     */
    public function enablePull(IDefineEntityType $entity_type)
    {
        /**
         * @var DefineEntityType $entity_type
         */
        $dto = $this->flow->enablePull($entity_type, $this->poolMachineName);

        return new FlowPullConfiguration($this->core, $dto);
    }
}
