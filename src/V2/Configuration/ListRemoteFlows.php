<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IListRemoteFlows;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\Raw\Model\PagedFlowList;
use EdgeBox\SyncCore\V2\SyncCore;

class ListRemoteFlows implements IListRemoteFlows
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * @var string
     */
    protected $remoteModuleVersion;

    /**
     * @var string[]
     */
    protected $pools = [];

    public function __construct(SyncCore $core, string $remote_module_version)
    {
        $this->core = $core;
        $this->remoteModuleVersion = $remote_module_version;
    }

    /**
     * {@inheritdoc}
     */
    public function thatUsePool(string $pool_id)
    {
        $this->pools[] = $pool_id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $result = [];
        $page = 0;

        do {
            $request = $this
                ->core
                ->getClient()
                ->flowControllerListRequest(
                    poolMachineNames: count($this->pools) ? $this->pools : null,
                    page: $page,
                    itemsPerPage: 100
                )
            ;

            /**
             * @var PagedFlowList $response
             */
            $response = $this
                ->core
                ->sendToSyncCoreAndExpect($request, PagedFlowList::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION)
            ;

            foreach ($response->getItems() as $item) {
                $result[] = new RemoteFlowItem($this->core, $item);
            }

            ++$page;
            $pages = $response->getNumberOfPages();
        } while ($page < $pages);

        return $result;
    }
}
