<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\Syndication\ISyndicationService;
use EdgeBox\SyncCore\V2\Helper;
use EdgeBox\SyncCore\V2\Raw\Model\DeleteRemoteEntityRevisionDto;
use EdgeBox\SyncCore\V2\Raw\Model\EntityRemoteStatus;
use EdgeBox\SyncCore\V2\Raw\Model\PagedRemoteEntityUsageListResponse;
use EdgeBox\SyncCore\V2\Raw\Model\RemoteEntityTypeEntity;
use EdgeBox\SyncCore\V2\SyncCore;

class SyndicationService implements ISyndicationService
{
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

    /**
     * {@inheritdoc}
     */
    public function massPull()
    {
        return new MassPull($this->core);
    }

    /**
     * {@inheritdoc}
     */
    public function massPush()
    {
        return new MassPush($this->core);
    }

    /**
     * {@inheritdoc}
     */
    public function configurePullDashboard()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function pullSingle(string $flow_id, string $type, string $bundle, string $entity_id)
    {
        return new TriggerPullSingle($this->core, $type, $bundle, $entity_id, $flow_id);
    }

    /**
     * {@inheritdoc}
     */
    public function pullAll(string $flow_id, string $type, string $bundle, string $version)
    {
        return new PullAll($this->core, $flow_id, $type, $bundle, $version);
    }

    /**
     * {@inheritdoc}
     *
     * @return PullOperation
     */
    public function handlePull(string $flow_id, ?string $type, ?string $bundle, array $data, bool $delete)
    {
        return new PullOperation($this->core, $data, $delete);
    }

    /**
     * {@inheritdoc}
     */
    public function pushSingle(string $flow_id, string $type, string $bundle, string $version_id, string $root_language, string $entity_uuid, ?string $entity_id)
    {
        return new PushSingle($this->core, $flow_id, $type, $bundle, $version_id, $root_language, $entity_uuid, $entity_id);
    }

    /**
     * {@inheritDoc}
     */
    public function pushMultiple(string $flow_id)
    {
        return new PushMultiple($this->core, $flow_id);
    }

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function refreshAuthentication()
    {
        // With our decentralized worker architecture we can't re-login so easily, so this
        // is not supported in Sync Core V2.
        return true;
    }
}
