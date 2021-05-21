<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefinePoolForFlow;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\SyncCore;

/**
 *
 */
class DefinePoolForFlow extends BatchOperation implements IDefinePoolForFlow {

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
   *
   * @param SyncCore $core
   * @param DefineFlow $flow
   * @param string $pool_machine_name
   */
  public function __construct(SyncCore $core, DefineFlow $flow, string $pool_machine_name) {
    parent::__construct(
      $core,
      NULL,
      NULL
    );

    $this->flow = $flow;
    $this->poolMachineName = $pool_machine_name;
  }

  /**
   * @return string
   */
  public function getPoolMachineName() {
    return $this->poolMachineName;
  }

  /**
   * @return DefineFlow
   */
  public function getFlow() {
    return $this->flow;
  }

  /**
   * @param DefineEntityType $type
   *
   * @return $this
   */
  public function enablePreview($type) {
    // Nothing to do in V2.
    return $this;
  }

  /**
   * @param DefineEntityType $type
   *
   * @return $this
   */
  public function useEntityType($type) {
    // Nothing to do in V2.
    return $this;
  }

  /**
   * @param DefineEntityType $type
   *
   * @return $this
   */
  public function enablePush($type) {
    $this->flow->enablePush($type, $this->poolMachineName);
    return $this;
  }

  /**
   * @param DefineEntityType $type
   *
   * @return FlowPullConfiguration
   */
  public function enablePull($type) {
    $dto = $this->flow->enablePull($type, $this->poolMachineName);
    return new FlowPullConfiguration($this->core, $dto);
  }

}
