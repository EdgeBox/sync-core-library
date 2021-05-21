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
    // TODO: Drupal: We need the actual entity ID; double check that Drupal provides it and not the machine name.
    public function getRemoteFlow(string $id)
    {
        $request = $this
        ->core
        ->getClient()
        ->flowControllerItemRequest($id);

        /**
         * @var FlowEntity $item
         */
        $item = $this
        ->core
        ->sendToSyncCoreAndExpect($request, FlowEntity::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);

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
    public function defineEntityType(string $pool_id, string $type_machine_name, string $bundle_machine_name, string $version_id)
    {
        return new DefineEntityType($this->core, $type_machine_name, $bundle_machine_name, $version_id);
    }

    /**
     * {@inheritdoc}
     */
    public function listRemotePools()
    {
        $request = $this
      ->core
      ->getClient()
      ->poolControllerListRequest();

        $remote_pools = $this
      ->core
      ->sendToSyncCore($request, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);

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
}
