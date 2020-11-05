<?php

namespace SyncCore\Interfaces\Syndication;

/**
 *
 */
interface IPullDashboardSearchResultItem {

  /**
   * @param array $properties
   */
  public function extend($properties);

  /**
   * @return string
   */
  public function getId();

  /**
   * @return string
   */
  public function getType();

  /**
   * @return string
   */
  public function getBundle();

  /**
   * @return array
   */
  public function toArray();

}
