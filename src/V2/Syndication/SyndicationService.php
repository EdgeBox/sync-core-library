<?php

namespace EdgeBox\SyncCore\V2\Syndication;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\Syndication\ISyndicationService;
use EdgeBox\SyncCore\V2\Helper;
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
    public function configurePullDashboard()
    {
        // TODO: Drupal: Handle NULL response; use EmbedService instead.
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function pullSingle(string $flow_id, string $type, string $bundle, string $entity_id)
    {
        return new TriggerPullSingle($this->core, $type, $bundle, $entity_id);
    }

    /**
     * {@inheritdoc}
     */
    public function pullAll(string $flow_id, string $type, string $bundle)
    {
        return new PullAll($this->core, $type, $bundle);
    }

    /**
     * {@inheritdoc}
     */
    public function handlePull(string $flow_id, string $type, string $bundle, array $data)
    {
        return new PullOperation($this->core, $type, $bundle, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function pushSingle(string $flow_id, string $type, string $bundle, string $version_id, string $entity_uuid, ?string $entity_id)
    {
        return new PushSingle($this->core, $type, $bundle, $entity_uuid, $version_id, $entity_id);
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
        $bundle,
        $type
    );
        $type = $this->core->sendToSyncCoreAndExpect($request, RemoteEntityTypeEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);

        do {
            $request = $this->core->getClient()->remoteEntityUsageControllerListRequest(
          100,
          $page,
          $type->getId(),
          null,
          $is_uuid ? null : $shared_entity_id,
          $is_uuid ? $shared_entity_id : null
      );
            /**
             * @var PagedRemoteEntityUsageListResponse $response
             */
            $response = $this->core->sendToSyncCoreAndExpect($request, PagedRemoteEntityUsageListResponse::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);

            foreach ($response->getItems() as $item) {
                // TODO: Library: This should be the site's UUID, not it's ID. Need to change the usage
                //  interface to return it, too.
                $result[$item->getSite()->getId()] = $item->getViewUrl();
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
        // TODO: Drupal: Tell Drupal that we don't support this so that the button doesn't show up.
        return true;
    }
}
