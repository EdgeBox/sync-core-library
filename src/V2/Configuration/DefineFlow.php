<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefineFlow;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\CreateFileDto;
use EdgeBox\SyncCore\V2\Raw\Model\CreateFlowDto;
use EdgeBox\SyncCore\V2\Raw\Model\EntityTypeVersionReference;
use EdgeBox\SyncCore\V2\Raw\Model\FileEntity;
use EdgeBox\SyncCore\V2\Raw\Model\FileType;
use EdgeBox\SyncCore\V2\Raw\Model\FlowSyndicationMode;
use EdgeBox\SyncCore\V2\Raw\Model\NewFlowSyndication;
use EdgeBox\SyncCore\V2\SyncCore;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

/**
 *
 */
class DefineFlow extends BatchOperation implements IDefineFlow {
  /**
   * @var CreateFlowDto $dto
   */
  protected $dto;

  /**
   * @var string $machineName
   */
  protected $machineName;

  // TODO: Interface: Make $config optional in IDefineFlow

  /**
   * DefineFlow constructor.
   *
   * @param SyncCore $core
   * @param string $machine_name
   * @param string $name
   * @param string|null $config
   */
  public function __construct(SyncCore $core, string $machine_name, string $name, $config) {
    $dto = new CreateFlowDto();
    $dto->setMachineName($machine_name);
    $dto->setName($name);

    parent::__construct(
      $core,
      BatchOperation::REQUEST_FLOW_CREATE,
      $dto
    );

    $this->machineName = $machine_name;

    if($config) {
      $file = $this->core->sendFile(FileType::REMOTE_FLOW_CONFIG, $machine_name . '.yml', $config);
      $dto->setRemoteConfigFileId($file->getId());
    }
  }

  /**
   * @return string
   */
  public function getMachineName() {
    return $this->machineName;
  }

  /**
   * @inheritdoc
   */
  // TODO: Interface: Add types to interface methods like usePool( >string< $pool_id)
  public function usePool($pool_id) {
    return new DefinePoolForFlow($this->core, $this, $pool_id);
  }

  /**
   * @param DefineEntityType $type
   * @param string $poolMachineName
   *
   * @return NewFlowSyndication
   */
  public function enablePull(DefineEntityType $type, string $poolMachineName) {
    $typeReference = new EntityTypeVersionReference();
    $typeReference->setNamespaceMachineName($type->getNamespaceMachineName());
    $typeReference->setMachineName($type->getMachineName());
    $typeReference->setVersionId($type->getVersionId());
    $allPools = $this->dto->getSitePullsByMachineName();

    foreach($allPools as &$config) {
      if($config->getPoolMachineName()===$poolMachineName) {
        $allTypes = $config->getEntityTypesByMachineName();
        $allTypes[] = $typeReference;
        $config->setEntityTypesByMachineName($allTypes);
        return $config;
      }
    }

    $config = new NewFlowSyndication();
    /**
     * @var FlowSyndicationMode $mode
     */
    $mode = FlowSyndicationMode::ALL;
    $config->setMode($mode);
    $config->setPoolMachineName($poolMachineName);
    $config->setEntityTypesByMachineName([]);

    $allPools[] = $config;
    $this->dto->setSitePullsByMachineName($allPools);
    return $config;
  }

  /**
   * @param DefineEntityType $type
   * @param string $poolMachineName
   *
   * @return NewFlowSyndication
   */
  public function enablePush(DefineEntityType $type, string $poolMachineName) {
    $typeReference = new EntityTypeVersionReference();
    $typeReference->setNamespaceMachineName($type->getNamespaceMachineName());
    $typeReference->setMachineName($type->getMachineName());
    $typeReference->setVersionId($type->getVersionId());

    $allPools = $this->dto->getSitePushesByMachineName();
    foreach($allPools as &$config) {
      if($config->getPoolMachineName()===$poolMachineName) {
        $allTypes = $config->getEntityTypesByMachineName();
        $allTypes[] = $typeReference;
        $config->setEntityTypesByMachineName($allTypes);
        return $config;
      }
    }

    $config = new NewFlowSyndication();
    /**
     * @var FlowSyndicationMode $mode
     */
    $mode = FlowSyndicationMode::ALL;
    $config->setMode($mode);
    $config->setPoolMachineName($poolMachineName);
    $config->setEntityTypesByMachineName([]);
    $allPools[] = $config;
    $this->dto->setSitePushesByMachineName($allPools);
    return $config;
  }
}
