<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Exception\BadRequestException;
use EdgeBox\SyncCore\Exception\ForbiddenException;
use EdgeBox\SyncCore\Exception\NotFoundException;
use EdgeBox\SyncCore\Exception\SyncCoreException;
use EdgeBox\SyncCore\Exception\TimeoutException;
use EdgeBox\SyncCore\Interfaces\Configuration\IDefineFlow;
use EdgeBox\SyncCore\V2\BatchOperation;
use EdgeBox\SyncCore\V2\Raw\Model\CreateFlowDto;
use EdgeBox\SyncCore\V2\Raw\Model\EntityTypeVersionReference;
use EdgeBox\SyncCore\V2\Raw\Model\FileType;
use EdgeBox\SyncCore\V2\Raw\Model\FlowSyndicationMode;
use EdgeBox\SyncCore\V2\Raw\Model\NewFlowSyndication;
use EdgeBox\SyncCore\V2\SyncCore;

class DefineFlow extends BatchOperation implements IDefineFlow
{
    /**
     * @var CreateFlowDto
     */
    protected $dto;

    /**
     * @var string
     */
    protected $machineName;

    /**
     * DefineFlow constructor.
     *
     * @throws BadRequestException
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws SyncCoreException
     * @throws TimeoutException
     */
    public function __construct(SyncCore $core, string $machine_name, string $name, ?string $config)
    {
        $dto = new CreateFlowDto();
        $dto->setMachineName($machine_name);
        $dto->setName($name);
        $dto->setSitePullsByMachineName([]);
        $dto->setSitePushesByMachineName([]);

        parent::__construct(
      $core,
      BatchOperation::REQUEST_FLOW_CREATE,
      $dto
    );

        $this->machineName = $machine_name;

        if ($config) {
            $file = $this->core->sendFile(
                FileType::REMOTE_FLOW_CONFIG,
                $machine_name.'.yml',
                $config,
                true
            );
            $dto->setRemoteConfigFileId($file->getId());
        }
    }

    /**
     * @return string
     */
    public function getMachineName()
    {
        return $this->machineName;
    }

    /**
     * {@inheritdoc}
     */
    public function usePool(string $pool_id)
    {
        return new DefinePoolForFlow($this->core, $this, $pool_id);
    }

    /**
     * @return NewFlowSyndication
     */
    public function enablePull(DefineEntityType $type, string $poolMachineName)
    {
        $typeReference = new EntityTypeVersionReference();
        $typeReference->setNamespaceMachineName($type->getNamespaceMachineName());
        $typeReference->setMachineName($type->getMachineName());
        $typeReference->setVersionId($type->getVersionId());

        $allPools = $this->dto->getSitePullsByMachineName();

        // TODO: SyncCore: Can't use optimization below as the config will be changed after being returned. Should optimize in the backend.
        /*foreach ($allPools as &$config) {
            if ($config->getPoolMachineName() === $poolMachineName) {
                $allTypes = $config->getEntityTypesByMachineName();
                $allTypes[] = $typeReference;
                $config->setEntityTypesByMachineName($allTypes);

                return $config;
            }
        }*/

        $config = new NewFlowSyndication();
        /**
         * @var FlowSyndicationMode $mode
         */
        $mode = FlowSyndicationMode::ALL;
        $config->setMode($mode);
        $config->setPoolMachineName($poolMachineName);
        $config->setEntityTypesByMachineName([
            $typeReference,
        ]);

        $allPools[] = $config;
        $this->dto->setSitePullsByMachineName($allPools);

        return $config;
    }

    /**
     * @return NewFlowSyndication
     */
    public function enablePush(DefineEntityType $type, string $poolMachineName)
    {
        $typeReference = new EntityTypeVersionReference();
        $typeReference->setNamespaceMachineName($type->getNamespaceMachineName());
        $typeReference->setMachineName($type->getMachineName());
        $typeReference->setVersionId($type->getVersionId());

        $allPools = $this->dto->getSitePushesByMachineName();

        // TODO: SyncCore: Can't use optimization below as the config will be changed after being returned. Should optimize in the backend.
        /*foreach ($allPools as &$config) {
            if ($config->getPoolMachineName() === $poolMachineName) {
                $allTypes = $config->getEntityTypesByMachineName();
                $allTypes[] = $typeReference;
                $config->setEntityTypesByMachineName($allTypes);

                return $config;
            }
        }*/

        $config = new NewFlowSyndication();
        /**
         * @var FlowSyndicationMode $mode
         */
        $mode = FlowSyndicationMode::ALL;
        $config->setMode($mode);
        $config->setPoolMachineName($poolMachineName);
        $config->setEntityTypesByMachineName([
            $typeReference,
        ]);
        $allPools[] = $config;
        $this->dto->setSitePushesByMachineName($allPools);

        return $config;
    }
}
