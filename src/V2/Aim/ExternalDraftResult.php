<?php

namespace EdgeBox\SyncCore\V2\Aim;

use EdgeBox\SyncCore\Interfaces\Aim\IExternalDraftResult;
use EdgeBox\SyncCore\V2\Raw\Model\ContentOptimizationEntity;

class ExternalDraftResult implements IExternalDraftResult
{
    /**
     * @var ContentOptimizationEntity
     */
    protected $optimization;

    /**
     * @var bool
     */
    protected $wasExisting;

    public function __construct(ContentOptimizationEntity $optimization, bool $was_existing)
    {
        $this->optimization = $optimization;
        $this->wasExisting = $was_existing;
    }

    public function getOptimizationId()
    {
        return (string) $this->optimization->getId();
    }

    public function getStatus()
    {
        return (string) $this->optimization->getStatus();
    }

    public function wasExisting()
    {
        return $this->wasExisting;
    }
}
