<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\Syndication\IPushMultiple;
use EdgeBox\SyncCore\V2\Raw\Model\CreateMigrationDto;
use EdgeBox\SyncCore\V2\Raw\Model\MigrationType;
use EdgeBox\SyncCore\V2\SyncCore;

class PushMultiple implements IPushMultiple
{
    /**
     * @var CreateMigrationDto
     */
    protected $dto;

    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * PushMultiple constructor.
     */
    public function __construct(SyncCore $core, string $flowMachineName)
    {
        $this->core = $core;

        $this->dto = new CreateMigrationDto();

        $this->dto->setType(MigrationType::PUSH_MANUALLY);
        $this->dto->setEntityReferences([]);
        $this->dto->setFlowMachineName($flowMachineName);
    }

    /**
     * {@inheritDoc}
     */
    public function addEntity(string $type, string $bundle, string $version_id, string $root_language, ?string $entity_uuid, ?string $entity_id)
    {
        $item = new PushMultipleItem($type, $bundle, $version_id, $root_language, $entity_uuid, $entity_id);

        $entities = $this->dto->getEntityReferences();
        $entities[] = $item->getDto();
        $this->dto->setEntityReferences($entities);

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->dto->jsonSerialize();
    }

    public function getDto()
    {
        return $this->dto;
    }

    /**
     * {@inheritdoc}
     */
    public function runInOrder(bool $set)
    {
        $this->dto->setRunInOrder($set);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($in_order = false)
    {
        $request = $this
            ->core
            ->getClient()
            ->migrationControllerCreateRequest($this->dto)
        ;

        $this
            ->core
            ->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT, false, SyncCore::PUSH_RETRY_COUNT)
        ;

        return $this;
    }
}
