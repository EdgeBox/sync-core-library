<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\Syndication\ITriggerPullSingle;
use EdgeBox\SyncCore\V2\Helper;
use EdgeBox\SyncCore\V2\Raw\Model\CreateSyndicationDto;
use EdgeBox\SyncCore\V2\SyncCore;

class TriggerPullSingle implements ITriggerPullSingle
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * @var CreateSyndicationDto
     */
    protected $dto;

    /**
     * TriggerPullSingle constructor.
     */
    public function __construct(SyncCore $core, string $namespace_machine_name, string $machine_name, string $shared_entity_id)
    {
        $this->core = $core;

        $this->dto = new CreateSyndicationDto();
        $this->dto->setPoolMachineNames([]);
        $this->dto->setEntityTypeNamespaceMachineName($namespace_machine_name);
        $this->dto->setEntityTypeMachineName($machine_name);

        if (Helper::isUuid($shared_entity_id)) {
            $this->dto->setRemoteUuid($shared_entity_id);
        } else {
            $this->dto->setRemoteUniqueId($shared_entity_id);
        }
    }

    /**
     * {@inheritdoc}
     */
    // TODO: Drupal: Support multiple pools.
    public function fromPool(string $pool_id)
    {
        $pools = $this->dto->getPoolMachineNames();
        $pools[] = $pool_id;
        $this->dto->setPoolMachineNames($pools);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function manually(bool $set)
    {
        $this->dto->setManually($set);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function asDependency(bool $set)
    {
        $this->dto->setAsDependency($set);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $request = $this->core->getClient()->syndicationControllerCreateRequest($this->dto);
        $this->core->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPullDashboardSearchResultItem()
    {
        // TODO: Drupal: As this is replaced with the embed service, we don't implement this here.
        //  Drupal must use embed instead in that case.
        return null;
    }
}
