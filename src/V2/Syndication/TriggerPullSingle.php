<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\Syndication\ITriggerPullSingle;
use EdgeBox\SyncCore\V2\Helper;
use EdgeBox\SyncCore\V2\Raw\Model\CreateTaskDto;
use EdgeBox\SyncCore\V2\Raw\Model\TaskEntity;
use EdgeBox\SyncCore\V2\SyncCore;

class TriggerPullSingle implements ITriggerPullSingle
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * @var CreateTaskDto
     */
    protected $dto;

    /**
     * The Syndication Entity, set when running ->execute().
     *
     * @var TaskEntity
     */
    protected $syndication;

    /**
     * The task the Sync Core created, set when running ->executeOnce().
     *
     * @var null|array<string, mixed>
     */
    private $task;

    /**
     * The UUID given through ->withUuid().
     *
     * @var null|string
     */
    private $uuid;

    /**
     * The unique ID given through ->withUniqueId().
     *
     * @var null|string
     */
    private $uniqueId;

    /**
     * The ID the pull was created with, kept once ->withUuid() or
     * ->withUniqueId() name the item's IDs by role.
     *
     * @var null|string
     */
    private $createdWith;

    /**
     * TriggerPullSingle constructor.
     */
    public function __construct(SyncCore $core, string $namespace_machine_name, string $machine_name, string $shared_entity_id, string $flow_id)
    {
        $this->core = $core;

        $this->dto = new CreateTaskDto();
        $this->dto->setFlowMachineName($flow_id);
        $this->dto->setPoolMachineNames([]);
        $this->dto->setEntityTypeNamespaceMachineName($namespace_machine_name);
        $this->dto->setEntityTypeMachineName($machine_name);

        if (Helper::isUuid($shared_entity_id)) {
            $this->dto->setRemoteUuid($shared_entity_id);
        } else {
            $this->dto->setRemoteUniqueId($shared_entity_id);
        }
    }

    // TODO: Drupal: Support multiple pools.
    public function fromPool(string $pool_id)
    {
        $pools = $this->dto->getPoolMachineNames();
        $pools[] = $pool_id;
        $this->dto->setPoolMachineNames($pools);

        return $this;
    }

    public function manually(bool $set)
    {
        $this->dto->setManually($set);

        return $this;
    }

    public function asDependency(bool $set)
    {
        $this->dto->setAsDependency($set);

        return $this;
    }

    public function withUuid(string $uuid)
    {
        $this->uuid = Helper::requireId($uuid);

        return $this;
    }

    public function withUniqueId(string $unique_id)
    {
        $this->uniqueId = Helper::requireId($unique_id);

        return $this;
    }

    public function execute()
    {
        $this->identify();
        $request = $this->core->getClient()->syndicationControllerCreateRequest(createTaskDto: $this->dto);
        $this->syndication = $this->core->sendToSyncCoreAndExpect($request, TaskEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT, false, SyncCore::PULL_RETRY_COUNT);

        return $this;
    }

    public function executeOnce(bool $configuration_access)
    {
        $this->identify();
        $ids = array_merge([$this->dto->getFlowMachineName()], $this->dto->getPoolMachineNames() ?? []);
        foreach ([$this->dto->getRemoteUuid(), $this->dto->getRemoteUniqueId()] as $id) {
            if (null !== $id) {
                $ids[] = $id;
            }
        }
        foreach ($ids as $id) {
            Helper::requireId($id);
        }
        $request = $this->core->getClient()->syndicationControllerCreateRequest(createTaskDto: $this->dto);

        return $this->task = Helper::decodeJsonObject($this->core->sendToSyncCore($request, Helper::permissions($configuration_access), false, SyncCore::PULL_ONCE_RETRY_COUNT));
    }

    public function getPullDashboardSearchResultItem()
    {
        return null;
    }

    /**
     * @return null|string the update ID to query for the status
     */
    public function getId()
    {
        if (isset($this->task['id']) && is_scalar($this->task['id'])) {
            return (string) $this->task['id'];
        }
        if ($this->syndication) {
            return $this->syndication->getId();
        }
    }

    /**
     * Put the IDs named by role into the request. The ID the pull was created
     * with takes the role no ID was named for, unless it is the ID named for
     * the other role; until an ID is named by role, the request keeps the ID
     * the pull was created with where its shape sorted it.
     */
    private function identify(): void
    {
        if (null === $this->uuid && null === $this->uniqueId) {
            return;
        }
        if (null === $this->createdWith) {
            $this->createdWith = $this->dto->getRemoteUuid() ?? $this->dto->getRemoteUniqueId();
        }

        $uuid = $this->uuid;
        $unique_id = $this->uniqueId;
        if (null === $uuid && !$this->isCreatedWith($unique_id)) {
            $uuid = $this->createdWith;
        } elseif (null === $unique_id && !$this->isCreatedWith($uuid)) {
            $unique_id = $this->createdWith;
        }
        $this->dto->setRemoteUuid($uuid);
        $this->dto->setRemoteUniqueId($unique_id);
    }

    /**
     * Whether the ID is the one the pull was created with, as a UUID is in
     * either case.
     */
    private function isCreatedWith(?string $id): bool
    {
        return null !== $id && null !== $this->createdWith && 0 === strcasecmp($id, $this->createdWith);
    }
}
