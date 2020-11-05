<?php

namespace EdgeBox\SyncCore\V1\Query\Result;

/**
 * Class SimpleResult.
 *
 * Simply provide the response body as a result.
 *
 * @package Drupal\cms_content_sync\SyncCore
 */
class SimpleResult extends Result {

  /**
   * @var array
   */
  protected $result;

  /**
   * Execute the query and store the result.
   *
   * @return $this
   *
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\TimeoutException
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\BadRequestException
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\ForbiddenException
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\NotFoundException
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException
   */
  public function execute() {
    $this->result = $this->query
      ->getCore()
      ->getClient()
      ->request($this->query);

    return $this;
  }

  /**
   * @return bool The result.
   */
  public function succeeded() {
    return !!$this->result;
  }

  /**
   * @return array The entity data.
   */
  public function getItem() {
    return $this->result;
  }

  /**
   * @return array
   */
  public function getResult() {
    return $this->result;
  }

}
