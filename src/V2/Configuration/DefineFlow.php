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
use EdgeBox\SyncCore\V2\Raw\Model\FlowStatus;
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
        /**
         * @var FlowStatus $status
         */
        $status = FlowStatus::_100_ACTIVE;
        $dto->setStatus($status);

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
                false,
                true
            );
            $dto->setRemoteConfigFileId($file->getId());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isActive($set = null)
    {
        if (true === $set || false === $set) {
            /**
             * @var FlowStatus $status
             */
            $status = FlowStatus::_100_ACTIVE;
            if (!$set) {
                $status = FlowStatus::_200_INACTIVE;
            }
            $this->dto->setStatus($status);
        }

        return FlowStatus::_200_INACTIVE !== $this->dto->getStatus();
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
     * {@inheritDoc}
     */
    public function allowedLanguages($set = null)
    {
        $this->dto->setAllowedLanguages($set);

        return $this->dto->getAllowedLanguages();
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

        $all = $this->dto->getSitePullsByMachineName();

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

        $all[] = $config;
        $this->dto->setSitePullsByMachineName($all);

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

        $all = $this->dto->getSitePushesByMachineName();

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
        $all[] = $config;
        $this->dto->setSitePushesByMachineName($all);

        return $config;
    }

    /**
     * {@inheritDoc}
     */
    protected function optimize()
    {
        /**
         * @var NewFlowSyndication[] $configs
         */
        $optimize = function ($configs) {
            for ($i = count($configs) - 1; $i > 0; --$i) {
                $check = $configs[$i];
                if ($check->getFilters()) {
                    continue;
                }

                // By serializing this, we can make sure that it will still work in
                // future updates even if we add new properties.
                // The only property we merge is entityTypesByMachineName, so we
                // rather whitelist that.
                $check_serialized = $check->jsonSerialize();
                $check_serialized->entityTypesByMachineName = [];

                for ($n = 0; $n < $i; ++$n) {
                    $add_to = $configs[$n];
                    if ($add_to->getFilters()) {
                        continue;
                    }

                    $add_to_serialized = $add_to->jsonSerialize();
                    $add_to_serialized->entityTypesByMachineName = [];

                    // The order doesn't matter so we're using == instead of ===.
                    if ($check_serialized == $add_to_serialized) {
                        $types = array_merge($add_to->getEntityTypesByMachineName(), $check->getEntityTypesByMachineName());
                        $add_to->setEntityTypesByMachineName($types);

                        array_splice($configs, $i, 1);

                        break;
                    }
                }
            }

            return $configs;
        };

        $pushes = $this->dto->getSitePushesByMachineName();
        $pushes_optimized = $optimize($pushes);
        if (count($pushes) !== count($pushes_optimized)) {
            $this->dto->setSitePushesByMachineName($pushes_optimized);
        }

        $pulls = $this->dto->getSitePullsByMachineName();
        $pulls_optimized = $optimize($pulls);
        if (count($pulls) !== count($pulls_optimized)) {
            $this->dto->setSitePullsByMachineName($pulls_optimized);
        }

        parent::optimize();
    }
}
