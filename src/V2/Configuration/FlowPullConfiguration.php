<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Exception\InternalContentSyncError;
use EdgeBox\SyncCore\Interfaces\Configuration\IFlowPullConfiguration;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\FlowSyndicationFilter;
use EdgeBox\SyncCore\V2\Raw\Model\FlowSyndicationFilterType;
use EdgeBox\SyncCore\V2\Raw\Model\FlowSyndicationMode;
use EdgeBox\SyncCore\V2\Raw\Model\NewFlowSyndication;
use EdgeBox\SyncCore\V2\SyncCore;

class FlowPullConfiguration extends BatchOperation implements IFlowPullConfiguration
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

    protected function setMode(string $mode)
    {
        /**
         * @var FlowSyndicationMode $mode
         */
        $this->dto->setMode($mode);
    }

    /**
     * @return $this
     */
    public function manually(bool $set)
    {
        if ($set) {
            $this->setMode(FlowSyndicationMode::MANUALLY);
        } else {
            $this->setMode(FlowSyndicationMode::ALL);
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
        } else {
            $this->setMode(FlowSyndicationMode::ALL);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function ifTaggedWith(string $property, array $allowed_entity_ids)
    {
        $newCondition = new FlowSyndicationFilter();
        /**
         * @var FlowSyndicationFilterType $type
         */
        $type = FlowSyndicationFilterType::PROPERTY_INCLUDES_REFERENCE;
        $newCondition->setType($type);
        $newCondition->setProperty($property);
        $newCondition->setIncludes($allowed_entity_ids);

        /**
         * @var FlowSyndicationFilter[] $conditions
         */
        $conditions = $this->dto->getFilters();
        if (!$conditions) {
            $conditions = [];
        }
        $conditions[] = $newCondition;

        $this->dto->setFilters($conditions);

        return $this;
    }

    /**
     * @return $this
     */
    public function pullDeletions(bool $set)
    {
        $this->dto->setSyndicateDeletions($set);

        return $this;
    }

    /**
     * @return IFlowPullConfiguration|void
     *
     * @throws InternalContentSyncError
     */
    public function configureOverride(string $flow_id)
    {
        throw new InternalContentSyncError("Sync Core v2 doesn't need flow overrides.");
    }
}
