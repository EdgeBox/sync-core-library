<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

/**
 *
 */
interface IPullDashboardSearchResult {

  /**
   * @return array
   */
  public function toArray();

  /**
   * @return IPullDashboardSearchResultItem[]
   */
  public function getItems();

}
