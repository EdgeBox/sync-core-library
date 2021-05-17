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
   * @throws \EdgeBox\SyncCore\Exception\TimeoutException
   * @throws \EdgeBox\SyncCore\Exception\BadRequestException
   * @throws \EdgeBox\SyncCore\Exception\ForbiddenException
   * @throws \EdgeBox\SyncCore\Exception\NotFoundException
   * @throws \EdgeBox\SyncCore\Exception\SyncCoreException
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
