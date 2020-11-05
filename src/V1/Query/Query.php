<?php

namespace EdgeBox\SyncCore\V1\Query;

use EdgeBox\SyncCoreV1\SyncCoreClient;

/**
 * Class Query
 * A query to execute against the Sync Core. Will return a Result object when
 * executed. This is just a simple helper class to simplify query creation in
 * an OOP fashion.
 *
 * @package Drupal\cms_content_sync\SyncCore
 */
abstract class Query {

  /**
   * @var array
   */
  protected $arguments;

  /**
   * @var \Drupal\cms_content_sync\SyncCore\V1\SyncCore
   */
  protected $core;

  /**
   * Query constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\SyncCore $core
   * @param array $arguments
   */
  public function __construct($core, $arguments = []) {
    $this->core = $core;
    $this->arguments = $arguments;
  }

  /**
   * @return \Drupal\cms_content_sync\SyncCore\V1\SyncCore
   */
  public function getCore() {
    return $this->core;
  }

  /**
   * Get the arguments stored.
   *
   * @return array
   */
  public function toArray() {
    return $this->arguments;
  }

  /**
   * Get the path to be appended after the Pool::backend_url for this Query.
   *
   * @return string
   */
  abstract public function getPath();

  /**
   * Get the HTTP method to use for the request.
   *
   * @return string
   */
  public function getMethod() {
    return SyncCoreClient::METHOD_GET;
  }

  /**
   * Get the request body to use.
   *
   * @return string|null
   */
  public function getBody() {
    return NULL;
  }

  /**
   * @return bool|string
   */
  public function returnBoolean() {
    return FALSE;
  }

  /**
   * Provide a Result object to get the actual entities from.
   *
   * @return \Drupal\cms_content_sync\SyncCore\V1\Query\Result\Result
   *
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\TimeoutException
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\BadRequestException
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\ForbiddenException
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\NotFoundException
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException
   */
  abstract public function execute();

}
