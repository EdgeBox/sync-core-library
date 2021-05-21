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

/**
 *
 */
class FlowPullConfiguration extends BatchOperation implements IFlowPullConfiguration {
  /**
   * @var NewFlowSyndication $dto
   */
  protected $dto;

  /**
   * FlowPullConfiguration constructor.
   * @param SyncCore $core
   * @param NewFlowSyndication $dto
   */
  public function __construct(SyncCore $core, NewFlowSyndication $dto) {
    parent::__construct($core, NULL, $dto);
  }

  /**
   * @param string $mode
   */
  protected function setMode(string $mode) {
    /**
     * @var FlowSyndicationMode $mode
     */
    $this->dto->setMode($mode);
  }

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function manually($set) {
    if($set) {
      $this->setMode(FlowSyndicationMode::MANUALLY);
    }
    else {
      $this->setMode(FlowSyndicationMode::ALL);
    }
    return $this;
  }

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function asDependency($set) {
    if($set) {
      $this->setMode(FlowSyndicationMode::DEPENDENT);
    }
    else {
      $this->setMode(FlowSyndicationMode::ALL);
    }
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function ifTaggedWith($property, $allowed_entity_ids) {
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
    if(!$conditions) {
      $conditions = [];
    }
    $conditions[] = $newCondition;

    $this->dto->setFilters($conditions);

    return $this;
  }

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function pullDeletions($set) {
    $this->dto->setSyndicateDeletions($set);
    return $this;
  }

  /**
   * @param string $flow_id
   * @return IFlowPullConfiguration|void
   *
   * @throws InternalContentSyncError
   */
  public function configureOverride($flow_id)
  {
    throw new InternalContentSyncError("Sync Core v2 doesn't need flow overrides.");
  }
}
