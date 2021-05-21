<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

interface IDefinePoolForFlow extends IBatchOperation
{
    /**
     * @return $this
     */
    public function useEntityType(IDefineEntityType $entity_type);

    /**
     * @return $this
     */
    public function enablePreview(IDefineEntityType $entity_type);

    /**
     * @return $this
     */
    public function enablePush(IDefineEntityType $entity_type);

    /**
     * @return IFlowPullConfiguration
     */
    public function enablePull(IDefineEntityType $entity_type);
}
