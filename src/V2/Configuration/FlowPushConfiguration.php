<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IFlowPushConfiguration;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\FlowSyndicationMode;
use EdgeBox\SyncCore\V2\Raw\Model\NewFlowSyndication;
use EdgeBox\SyncCore\V2\SyncCore;

class FlowPushConfiguration extends BatchOperation implements IFlowPushConfiguration
{
    /**
     * @var NewFlowSyndication
     */
    protected $dto;

    /**
     * FlowPullConfiguration constructor.
     */
    public function __construct(SyncCore $core, NewFlowSyndication $dto)
    {
        parent::__construct($core, null, $dto);
    }

    /**
     * @return $this
     */
    public function manually(bool $set)
    {
        if ($set) {
            $this->setMode(FlowSyndicationMode::MANUALLY);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function asDependency(bool $set)
    {
        if ($set) {
            $this->setMode(FlowSyndicationMode::DEPENDENT);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function pushDeletions(bool $set)
    {
        $this->dto->setSyndicateDeletions($set);

        return $this;
    }

    protected function setMode(string $mode)
    {
        /**
         * @var FlowSyndicationMode $mode
         */
        $this->dto->setMode($mode);
    }
}
