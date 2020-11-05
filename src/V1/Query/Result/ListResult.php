<?php

namespace EdgeBox\SyncCore\V1\Query\Result;

use EdgeBox\SyncCoreV1\SyncCoreClient;

/**
 * Class ListResult
 * Helper class to retrieve individual pages of a ListQuery or all items at once
 * across all pages.
 *
 * @package Drupal\cms_content_sync\SyncCore
 */
class ListResult extends Result {

  /**
   * @var int
   *    Cache how many pages can be returned eventually.
   */
  protected $numberOfPages = NULL;

  /**
   * @var int
   *    Cache how many items will be provided in total.
   */
  protected $totalNumberOfItems = NULL;

  /**
   * @return int
   *
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException
   */
  public function getNumberOfItems() {
    if ($this->totalNumberOfItems === NULL) {
      $this->getPage();
    }

    return $this->totalNumberOfItems;
  }

  /**
   * Get the items if an individual page.
   *
   * @param int $page
   *   The page to retrieve.
   *
   * @return array The entities as an array.
   *
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException
   */
  public function getPage($page = 1) {
    /**
     * @var \Drupal\cms_content_sync\SyncCore\V1\Query\ListQuery $query
     */
    $query = $this->query;

    $query->setPage($page);

    $client = $this->query->getCore();

    $data = $client->getClient()->request($this->query);

    if ($this->numberOfPages === NULL) {
      $this->numberOfPages = $data['number_of_pages'];
    }
    if ($this->totalNumberOfItems === NULL) {
      $this->totalNumberOfItems = $data['total_number_of_items'];
    }

    return $data['items'];
  }

  /**
   * Get the raw response body as an array. Items are at 'items'.
   * You should only use this if you provide it to other Sync Core applications, e.g. the Pull dashboard.
   *
   * @return array
   *
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException
   */
  public function getRaw() {
    $client = $this->query->getCore();

    $data = $client->getClient()->request($this->query);

    if ($this->numberOfPages === NULL) {
      $this->numberOfPages = $data['number_of_pages'];
    }
    if ($this->totalNumberOfItems === NULL) {
      $this->totalNumberOfItems = $data['total_number_of_items'];
    }

    return $data;
  }

  /**
   * Get all remote entities at once.
   *
   * @return array All entities for the given Query as an array.
   *
   * @throws \Exception
   */
  public function getAll() {
    /**
     * @var \Drupal\cms_content_sync\SyncCore\V1\Query\ListQuery $query
     */
    $query = $this->query;

    $page = 1;
    $result = [];

    $items_per_page = $query->getItemsPerPage();
    $query->setItemsPerPage(SyncCoreClient::MAX_ITEMS_PER_PAGE);

    do {
      $items = $this->getPage($page++);
      $result = array_merge($result, $items);
    } while ($page <= $this->numberOfPages);

    $query->setItemsPerPage($items_per_page);

    return $result;
  }

}
