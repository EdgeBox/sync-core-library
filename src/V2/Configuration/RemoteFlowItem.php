<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IRemoteFlow;
use EdgeBox\SyncCore\Interfaces\Configuration\IRemoteFlowListItem;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\Raw\Model\FileEntity;
use EdgeBox\SyncCore\V2\Raw\Model\FlowEntity;
use EdgeBox\SyncCore\V2\Raw\Model\FlowSummary;
use EdgeBox\SyncCore\V2\Raw\Model\SiteEntity;
use EdgeBox\SyncCore\V2\SyncCore;

class RemoteFlowItem implements IRemoteFlowListItem, IRemoteFlow
{
    /**
     * @var FlowEntity|FlowSummary
     */
    protected $item;

    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * RemoteFlowItem constructor.
     *
     * @param FlowEntity|FlowSummary $item
     */
    public function __construct(SyncCore $core, $item)
    {
        $this->core = $core;
        $this->item = $item;
    }

    public function getId()
    {
        return $this->item->getId();
    }

    public function getMachineName()
    {
        return $this->item->getMachineName();
    }

    public function getName()
    {
        return $this->item->getName();
    }

    public function getSiteName()
    {
        $id = $this->item->getSite()->getId();

        $request = $this
            ->core
            ->getClient()
            ->siteControllerItemRequest($id)
        ;

        $response = $this
            ->core
            ->sendToSyncCoreAndExpect($request, SiteEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONTENT)
        ;

        return $response['name'];
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        if ($this->item instanceof FlowEntity) {
            $item = $this->item;
        } else {
            $request = $this
                ->core
                ->getClient()
                ->flowControllerItemRequest($this->item->getId())
            ;

            /**
             * @var FlowEntity $item
             */
            $item = $this
                ->core
                ->sendToSyncCoreAndExpect($request, FlowEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION)
            ;
        }

        $config_file = $item->getRemoteConfigAsFile();
        if (!$config_file) {
            return null;
        }

        $file_id = $config_file->getId();
        if (!$file_id) {
            return null;
        }

        $request = $this->core->getClient()->fileControllerItemRequest($file_id);
        /**
         * @var FileEntity $file
         */
        $file = $this->core->sendToSyncCoreAndExpect($request, FileEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);

        if (empty($file->getDownloadUrl())) {
            return null;
        }

        return file_get_contents($file->getDownloadUrl());
    }
}
