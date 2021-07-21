<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IConfigurationService;
use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\V2\Raw\Model\FlowEntity;
use EdgeBox\SyncCore\V2\SyncCore;

class ConfigurationService implements IConfigurationService
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
    public function listRemoteFlows(string $remote_module_version)
    {
        return new ListRemoteFlows($this->core, $remote_module_version);
    }

    /**
     * {@inheritdoc}
     */
    public function getRemoteFlow(string $id)
    {
        $request = $this
            ->core
            ->getClient()
            ->flowControllerItemRequest($id)
        ;

        /**
         * @var FlowEntity $item
         */
        $item = $this
            ->core
            ->sendToSyncCoreAndExpect($request, FlowEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION)
        ;

        return new RemoteFlowItem($this->core, $item);
    }

    /**
     * {@inheritdoc}
     */
    public function defineFlow(string $machine_name, string $name, ?string $config)
    {
        return new DefineFlow($this->core, $machine_name, $name, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function defineEntityType(string $pool_id, string $type_machine_name, string $bundle_machine_name, string $version_id, ?string $name = null)
    {
        return new DefineEntityType($this->core, $type_machine_name, $bundle_machine_name, $version_id, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function listRemotePools()
    {
        $request = $this
            ->core
            ->getClient()
            ->poolControllerListRequest()
        ;

        $response = $this
            ->core
            ->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION)
        ;

        $remote_pools = json_decode($response, true);

        $options = [];
        foreach ($remote_pools as $option) {
            $machine_name = $option['machineName'];
            $options[$machine_name] = $option['name'];
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function usePool(string $pool_id, string $pool_name)
    {
        return new RegisterPool($this->core, $pool_id, $pool_name);
    }

    /**
     * {@inheritdoc}
     */
    public function enableEntityPreviews($public_access_possible = false)
    {
        // Not configurable by sites in the new Sync Core.
        return $this;
    }

    /**
     * @return $this
     */
    public function deleteFlows(array $keep_machine_names)
    {
        $dto = new \EdgeBox\SyncCore\V2\Raw\Model\FlowDeleteRequest();
        $dto->setKeepFlowMachineNames($keep_machine_names);

        $request = $this
            ->core
            ->getClient()
            ->flowControllerDeleteRequest($dto)
        ;

        $this
            ->core
            ->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION)
        ;

        return $this;
    }
}
