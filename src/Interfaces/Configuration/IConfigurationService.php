<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

/**
 *
 */
interface IConfigurationService {

  /**
   * @param string $remote_module_version
   *
   * @return IListRemoteFlows
   */
  public function listRemoteFlows($remote_module_version);

  /**
   * @param string $id
   *
   * @return IRemoteFlow
   */
  public function getRemoteFlow($id);

  /**
   * @return array pool ID => pool name.
   *
   * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
   */
  public function listRemotePools();

  /**
   * @param string $pool_id
   * @param string $pool_name
   *
   * @return IRegisterPool
   *
   * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
   */
  public function usePool($pool_id, $pool_name);

  /**
   * @param bool $public_access_possible
   *
   * @return $this
   */
  public function enableEntityPreviews($public_access_possible = FALSE);

  /**
   * @param string $machine_name
   * @param string $name
   * @param string $config
   *
   * @return IDefineFlow
   */
  public function defineFlow($machine_name, $name, $config);

  /**
   * @param string $pool_id
   * @param string $type_machine_name
   * @param string $bundle_machine_name
   * @param string $version_id
   *
   * @return IDefineEntityType
   */
  public function defineEntityType($pool_id, $type_machine_name, $bundle_machine_name, $version_id);

}
