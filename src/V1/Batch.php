<?php

namespace SyncCore\V1;

use Drupal\cms_content_sync\SyncCore\Interfaces\IBatch;

/**
 *
 */
class Batch implements IBatch {

  /**
   * @var BatchOperation[]
   */
  protected $operations = [];

  /**
   * @var \Drupal\cms_content_sync\SyncCore\V1\SyncCore
   */
  protected $core;

  /**
   * Batch constructor.
   *
   * @param \Drupal\cms_content_sync\SyncCore\V1\SyncCore $core
   */
  public function __construct($core) {
    $this->core = $core;
  }

  /**
   * @inheritdoc
   */
  public function add($operation) {
    $this->operations[] = $operation;
  }

  /**
   * @inheritdoc
   */
  public function count() {
    return count($this->operations);
  }

  /**
   * @inheritdoc
   */
  public function get($index) {
    return $this->operations[$index];
  }

  /**
   * @inheritdoc
   */
  public function executeAll() {
    foreach ($this->operations as $operation) {
      $operation->execute();
    }
  }

  /**
   * @param Batch $other
   */
  public function prepend($other) {
    $this->operations = array_merge(
      $other->getOperations(),
      $this->operations
    );
  }

  /**
   * @return BatchOperation[]
   */
  public function getOperations() {
    return $this->operations;
  }

  /**
   * @return array
   */
  public function __sleep() {
    return [
      'operations',
    ];
  }

}
