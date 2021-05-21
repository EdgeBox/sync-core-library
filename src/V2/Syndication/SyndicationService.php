<?php

namespace EdgeBox\SyncCore\V2\Syndication;

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
    public function pullSingle($flow_id, $type, $bundle, $entity_id)
    {
        return new TriggerPullSingle($this->core, $type, $bundle, $entity_id);
    }

    /**
     * {@inheritdoc}
     */
    public function pullAll($flow_id, $type, $bundle)
    {
        return new PullAll($this->core, $type, $bundle);
    }

    /**
     * {@inheritdoc}
     */
    public function handlePull($flow_id, $type, $bundle, $data)
    {
        return new PullOperation($this->core, $type, $bundle, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function pushSingle($flow_id, $type, $bundle, $entity_uuid, $entity_id)
    {
        return new PushSingle($this->core, $type, $bundle, $entity_uuid, $entity_id);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalUsages($pool_id, $entity_type, $bundle, $shared_entity_id)
    {
        $is_uuid = Helper::isUuid($shared_entity_id);

        $result = [];
        $page = 0;

        $request = $this->core->getClient()->remoteEntityTypeControllerByMachineNameRequest(
        $bundle,
        $entity_type
    );
        $entity_type = $this->core->sendToSyncCoreAndExpect($request, RemoteEntityTypeEntity::class, SyncCore::SYNC_CORE_PERMISSIONS_CONFIGURATION);

        do {
            $request = $this->core->getClient()->remoteEntityUsageControllerListRequest(
          100,
          $page,
          $entity_type->getId(),
          null,
          $is_uuid ? null : $shared_entity_id,
          $is_uuid ? $shared_entity_id : null
      );
            $response = $this->core->sendToSyncCoreAndExpect($request, PagedRemoteEntityUsageListResponse::class, SyncCore::SYNC_CORE_PERMISSIONS_CONFIGURATION);

            foreach ($response->getItems() as $item) {
                $result[$item['site']['id']] = $item['viewUrl'];
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
