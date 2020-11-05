<?php

namespace SyncCore\Interfaces\Configuration;

/**
 *
 */
interface IRemoteFlowListItem {

  /**
   * @return string
   */
  public function getId();

  /**
   * @return string
   */
  public function getName();

  /**
   * @return string
   */
  public function getSiteName();

}
