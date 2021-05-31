<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

use EdgeBox\SyncCore\Exception\SyncCoreException;

interface IConfigurationService
{
    /**
     * @return IListRemoteFlows
     */
    public function listRemoteFlows(string $remote_module_version);

    /**
     * @return IRemoteFlow
     */
    public function getRemoteFlow(string $id);

    /**
     * @return array pool ID => pool name
     *
     * @throws SyncCoreException
     */
    public function listRemotePools();

    /**
     * @return IRegisterPool
     *
     * @throws SyncCoreException
     */
    public function usePool(string $pool_id, string $pool_name);

    /**
     * @param bool $public_access_possible
     *
     * @return $this
     */
    public function enableEntityPreviews($public_access_possible = false);

    /**
     * @return IDefineFlow
     */
    public function defineFlow(string $machine_name, string $name, ?string $config);

    /**
     * @return IDefineEntityType
     */
    public function defineEntityType(string $pool_id, string $type_machine_name, string $bundle_machine_name, string $version_id, ?string $name=NULL);
}
