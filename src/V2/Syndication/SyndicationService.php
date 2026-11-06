<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\Syndication\ISyndicationService;
use EdgeBox\SyncCore\V2\Helper;
use EdgeBox\SyncCore\V2\Raw\Model\DeleteRemoteEntityRevisionDto;
use EdgeBox\SyncCore\V2\Raw\Model\EntityRemoteStatus;
use EdgeBox\SyncCore\V2\Raw\Model\MigrationSummary;
use EdgeBox\SyncCore\V2\Raw\Model\PagedMigrationList;
use EdgeBox\SyncCore\V2\Raw\Model\PagedRemoteEntityUsageListResponse;
use EdgeBox\SyncCore\V2\Raw\Model\PreviewsListResponse;
use EdgeBox\SyncCore\V2\Raw\Model\PullDashboardConfiguration;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeEntity;
use EdgeBox\SyncCore\V2\Raw\Model\SyndicationEntityWithUsage;
use EdgeBox\SyncCore\V2\Raw\Model\TaskGroupEntity;
use EdgeBox\SyncCore\V2\Raw\Model\TaskGroupType;
use EdgeBox\SyncCore\V2\SyncCore;

class SyndicationService implements ISyndicationService
{
    /**
     * Asks the Sync Core to add the usage of a task's item on this site.
     */
    private const INCLUDE_USAGE = 'true';

    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * SyndicationService constructor.
     */
    public function __construct(SyncCore $core)
    {
        $this->core = $core;
    }

    public function massPull()
    {
        return new MassPull($this->core);
    }

    public function massPush()
    {
        return new MassPush($this->core);
    }

    public function configurePullDashboard()
    {
        return null;
    }

    public function pullSingle(string $flow_id, string $type, string $bundle, string $entity_id)
    {
        return new TriggerPullSingle($this->core, $type, $bundle, $entity_id, $flow_id);
    }

    public function pullAll(string $flow_id, string $type, string $bundle, string $version)
    {
        return new PullAll($this->core, $flow_id, $type, $bundle, $version);
    }

    /**
     * @return PullOperation
     */
    public function handlePull(string $flow_id, ?string $type, ?string $bundle, array $data, bool $delete)
    {
        return new PullOperation($this->core, $data, $delete);
    }

    public function pushSingle(string $flow_id, string $type, string $bundle, string $version_id, string $root_language, string $entity_uuid, ?string $entity_id)
    {
        return new PushSingle($this->core, $flow_id, $type, $bundle, $version_id, $root_language, $entity_uuid, $entity_id);
    }

    public function pushMultiple(string $flow_id)
    {
        return new PushMultiple($this->core, $flow_id);
    }

    public function deletedLocally(string $flow_id, string $type, string $bundle, string $language, string $entity_uuid, ?string $entity_id)
    {
        $dto = new DeleteRemoteEntityRevisionDto();
        $dto->setFlowMachineName($flow_id);
        $dto->setEntityTypeNamespaceMachineName($type);
        $dto->setEntityTypeMachineName($bundle);
        $dto->setLanguage($language);
        $dto->setRemoteUuid($entity_uuid);
        $dto->setRemoteUniqueId($entity_id);

        $request = $this
            ->core
            ->getClient()
            ->remoteEntityRevisionControllerDeleteRequest(deleteRemoteEntityRevisionDto: $dto)
        ;

        $this->core->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT, false, SyncCore::PULL_RETRY_COUNT);
    }

    public function getExternalUsages(string $pool_id, string $type, string $bundle, string $shared_entity_id)
    {
        $is_uuid = Helper::isUuid($shared_entity_id);

        $result = [];
        $page = 0;

        $request = $this->core->getClient()->remoteEntityTypeControllerByMachineNameRequest(
            machineName: $bundle,
            namespaceMachineName: $type
        );
        // TODO: Sync Core: Provide filter by pool name and entity type machine names.
        $type = $this->core->sendToSyncCoreAndExpect($request, RemoteEntityTypeEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION, false, SyncCore::UPDATES_GET_RETRY_COUNT);

        do {
            $request = $this->core->getClient()->remoteEntityUsageControllerListRequest(
                entityTypeId: $type->getId(),
                remoteUniqueId: $is_uuid ? null : $shared_entity_id,
                remoteUuid: $is_uuid ? $shared_entity_id : null,
                page: $page,
                itemsPerPage: 100
            );

            /**
             * @var PagedRemoteEntityUsageListResponse $response
             */
            $response = $this->core->sendToSyncCoreAndExpect($request, PagedRemoteEntityUsageListResponse::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION, false, SyncCore::UPDATES_GET_RETRY_COUNT);

            foreach ($response->getItems() as $item) {
                if (EntityRemoteStatus::EXISTS === $item->getStatus()) {
                    $result[$item->getSite()->getId()] = $item->getViewUrl();
                }
            }

            ++$page;
        } while ($page < $response->getNumberOfPages());

        return $result;
    }

    public function getPullDashboardConfiguration(bool $configuration_access)
    {
        $request = $this->core->getClient()->configurationControllerPullDashboardRequest();

        return $this->core->sendToSyncCoreAndExpect($request, PullDashboardConfiguration::class, Helper::permissions($configuration_access), false, SyncCore::CONFIG_GET_RETRY_COUNT);
    }

    public function listPreviews(string $flow_id, array $query, bool $configuration_access)
    {
        $request = $this->core->getClient()->previewsControllerListForFlowRequest(
            flowMachineName: Helper::requireId($flow_id),
            existsLocally: $query['existsLocally'] ?? null,
            deletedLocally: $query['deletedLocally'] ?? null,
            deleted: $query['deleted'] ?? null,
            sourceSiteId: $query['sourceSiteId'] ?? null,
            entityTypeMachineNames: $query['entityTypeMachineNames'] ?? null,
            entityTypeNamespaceMachineNames: $query['entityTypeNamespaceMachineNames'] ?? null,
            publishedLatest: $query['publishedLatest'] ?? null,
            publishedEarliest: $query['publishedEarliest'] ?? null,
            poolMachineNames: $query['poolMachineNames'] ?? null,
            itemsPerPage: $query['itemsPerPage'] ?? null,
            page: $query['page'] ?? null,
            search: $query['search'] ?? null
        );

        return $this->core->sendToSyncCoreAndExpect($request, PreviewsListResponse::class, Helper::permissions($configuration_access), false, SyncCore::PREVIEWS_GET_RETRY_COUNT);
    }

    public function getTask(string $task_id)
    {
        $request = $this->core->getClient()->taskControllerItemRequest(id: Helper::requireId($task_id), includeUsage: self::INCLUDE_USAGE);

        return $this->core->sendToSyncCoreAndExpect($request, SyndicationEntityWithUsage::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT, false, SyncCore::UPDATES_GET_RETRY_COUNT);
    }

    public function getTaskGroup(string $task_group_id, bool $configuration_access)
    {
        $request = $this->core->getClient()->migrationControllerItemRequest(id: Helper::requireId($task_group_id));

        return $this->core->sendToSyncCoreAndExpect($request, TaskGroupEntity::class, Helper::permissions($configuration_access), false, SyncCore::UPDATES_GET_RETRY_COUNT);
    }

    public function getTaskGroupSummary(string $task_group_id)
    {
        $request = $this->core->getClient()->migrationControllerSummaryRequest(id: Helper::requireId($task_group_id));

        return $this->core->sendToSyncCoreAndExpect($request, MigrationSummary::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION, false, SyncCore::UPDATES_GET_RETRY_COUNT);
    }

    public function getNewestManualPushTaskGroup(string $flow_id)
    {
        $request = $this->core->getClient()->migrationControllerListRequest(
            flowMachineName: Helper::requireId($flow_id),
            types: TaskGroupType::PUSH_MANUALLY,
            siteUuid: $this->core->getApplication()->getSiteUuid(),
            itemsPerPage: 1
        );

        /** @var PagedMigrationList $page */
        $page = $this->core->sendToSyncCoreAndExpect($request, PagedMigrationList::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT, false, SyncCore::UPDATES_GET_RETRY_COUNT);

        return $page->getItems()[0] ?? null;
    }

    public function refreshAuthentication()
    {
        // With our decentralized worker architecture we can't re-login so easily, so this
        // is not supported in Sync Core V2.
        return true;
    }
}
