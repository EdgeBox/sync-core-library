<?php

namespace EdgeBox\SyncCore\V2\Aim;

use EdgeBox\SyncCore\Interfaces\Aim\ActingUser;
use EdgeBox\SyncCore\Interfaces\Aim\IAimService;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\Raw\Model\ContentItemEntity;
use EdgeBox\SyncCore\V2\Raw\Model\TaxonomyTermEntity;
use EdgeBox\SyncCore\V2\SyncCore;

class AimService implements IAimService
{
    /**
     * @var SyncCore
     */
    protected $core;

    public function __construct(SyncCore $core)
    {
        $this->core = $core;
    }

    public function parseOptimizeContentRequest(array $query, array $body)
    {
        return new OptimizeContentRequest($query, $body);
    }

    public function postExternalDraft(string $content_item_key, string $external_revision_id, string $rendered_html, ?ActingUser $as = null)
    {
        return new PostExternalDraft($this->core, $content_item_key, $external_revision_id, $rendered_html, $as);
    }

    public function getContentItemByKey(string $key, string $status, ?ActingUser $as = null)
    {
        $request = $this->core->getClient()->contentItemControllerItemByKeyRequest(key: $key, status: $status);

        /** @var ContentItemEntity $entity */
        $entity = $this->core->sendToSyncCoreAsUserAndExpect(
            $request,
            ContentItemEntity::class,
            $as,
            IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT,
            false,
            SyncCore::CONFIG_GET_RETRY_COUNT
        );

        return new ContentItemSummary($entity);
    }

    public function getTaxonomyTermByKey(string $key, string $status, ?ActingUser $as = null)
    {
        $request = $this->core->getClient()->taxonomyTermControllerItemByKeyRequest(key: $key, status: $status);

        /** @var TaxonomyTermEntity $entity */
        $entity = $this->core->sendToSyncCoreAsUserAndExpect(
            $request,
            TaxonomyTermEntity::class,
            $as,
            IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT,
            false,
            SyncCore::CONFIG_GET_RETRY_COUNT
        );

        return new TaxonomyTermSummary($entity);
    }
}
