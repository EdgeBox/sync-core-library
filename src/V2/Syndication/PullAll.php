<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Exception\InternalContentSyncError;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\Syndication\IPullAll;
use EdgeBox\SyncCore\V2\Raw\Model\CreateMigrationDto;
use EdgeBox\SyncCore\V2\Raw\Model\MigrationEntity;
use EdgeBox\SyncCore\V2\Raw\Model\MigrationType;
use EdgeBox\SyncCore\V2\Raw\Model\SyndicationStatus;
use EdgeBox\SyncCore\V2\SerializableWithSyncCoreReference;
use EdgeBox\SyncCore\V2\SyncCore;

// TODO: Provide a "Push all" action as well so Drupal doesn't push all the entities, but
//  the Sync Core will rather pull them from the site, one after another.

// TODO: Viewing the progress would be better handled by the embed frontend.

class PullAll extends SerializableWithSyncCoreReference implements IPullAll
{
    /**
     * @var string
     */
    protected $namespaceMachineName;

    /**
     * @var string
     */
    protected $machineName;

    /**
     * @var string
     */
    protected $flow;

    /**
     * @var bool
     */
    protected $pullAll;

    /**
     * @var string|null
     */
    protected $migrationId = null;

    /**
     * @var MigrationEntity|null
     */
    protected $dto = null;

    /**
     * PullAll constructor.
     */
    public function __construct(SyncCore $core, string $namespaceMachineName, string $machineName)
    {
        parent::__construct($core);

        $this->namespaceMachineName = $namespaceMachineName;
        $this->machineName = $machineName;
    }

    /**
     * {@inheritdoc}
     */
    public function fromPool(string $pool_id)
    {
        throw new InternalContentSyncError('Must use Flow instead of Pool.');
    }

    // TODO: Drupal/Library: V2 pulls from Flows, not from Pools. So we need a switch in the module.

    /**
     * {@inheritdoc}
     */
    public function fromFlow($flow_id)
    {
        $this->flow = $flow_id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function force(bool $set)
    {
        $this->pullAll = $set;

        return $this;
    }

    // TODO: Drupal: Use this to check if it's done as we now have a status property.
    public function hasFinished()
    {
        return SyndicationStatus::_400_FINISHED === $this->getDto()->getStatus();
    }

    public function hasFailed()
    {
        return SyndicationStatus::_500_FAILED === $this->getDto()->getStatus();
    }

    public function wasAborted()
    {
        return SyndicationStatus::_600_ABORTED === $this->getDto()->getStatus();
    }

    /**
     * {@inheritdoc}
     */
    public function total()
    {
        return $this->getDto()->getTotalNumberOfSyndications();
    }

    protected function getDto()
    {
        if ($this->dto) {
            return $this->dto;
        }

        $request = $this->core->getClient()->migrationControllerItemRequest($this->migrationId);
        $response = $this->core->sendToSyncCoreAndExpect($request, MigrationEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);

        return $this->dto = $response;
    }

    /**
     * {@inheritdoc}
     */
    public function progress($fromCache = false)
    {
        if (!$this->migrationId) {
            throw new InternalContentSyncError("Can't get syndication progress before executing the pull all operation.");
        }

        $migration = $this->getDto();

        return $migration->getSuccessfulSyndications() + $migration->getFailedSyndications();
    }

    /**
     * {@inheritdoc}
     */
    public function getPoolMachineName()
    {
        throw new InternalContentSyncError('Must use Flow instead of Pool.');
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeMachineName()
    {
        return $this->namespaceMachineName;
    }

    /**
     * {@inheritdoc}
     */
    public function getBundleMachineName()
    {
        return $this->machineName;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $migrationDto = new CreateMigrationDto();
        /**
         * @var MigrationType $type
         */
        $type = $this->pullAll
        ? MigrationType::PULL_ALL
        : MigrationType::PULL_CHANGED;
        $migrationDto->setType($type);
        $migrationDto->setInitialSetup(false);
        $migrationDto->setFlowMachineName($this->flow);

        $request = $this->core->getClient()->migrationControllerCreateRequest($migrationDto);
        $response = $this->core->sendToSyncCoreAndExpect($request, MigrationEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);
        $this->dto = $response;

        $this->migrationId = $this->dto->getId();

        return $this;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            'core' => $this->serializeSyncCore(),
            'namespaceMachineName' => $this->namespaceMachineName,
            'machineName' => $this->machineName,
            'migrationId' => $this->migrationId,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        $this->unserializeSyncCore($data['core']);

        $this->namespaceMachineName = $data['namespaceMachineName'];
        $this->machineName = $data['machineName'];
        $this->migrationId = $data['migrationId'];
    }
}
