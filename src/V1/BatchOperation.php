<?php

namespace EdgeBox\SyncCore\V1;

use EdgeBox\SyncCore\Interfaces\IBatchOperation;

/**
 *
 */
class BatchOperation extends SerializableWithSyncCoreReference implements IBatchOperation {

  /**
   * @var string
   */
  protected $type;

  /**
   * @var array
   */
  protected $body;

  /**
   * @var arrayAdditionaloperationstoexecuteafterthemainoperationEg
   *            when defining an entity type we immediately create a pool
   *            connection for it.
   */
  protected $downstream = [];

  /**
   * Batchable constructor.
   *
   * @param SyncCore $core
   * @param string $type
   * @param array $body
   */
  public function __construct($core, $type, $body) {
    parent::__construct($core);

    $this->type = $type;
    $this->body = $body;
  }

  /**
   * @param string $type
   * @param array $body
   */
  public function addDownstream($type, $body) {
    $this->downstream[] = [
      'type' => $type,
      'body' => $body,
    ];
  }

  /**
   * @inheritdoc
   */
  public function addToBatch($batch) {
    $batch->add($this);

    return $this;
  }

  /**
   * @throws \Drupal\cms_content_sync\SyncCore\Exception\SyncCoreException
   */
  public function execute() {
    $operations = array_merge([
      [
        'type' => $this->type,
        'body' => $this->body,
      ],
    ], $this->downstream);

    foreach ($operations as $op) {
      $this
        ->core
        ->storage
        ->getStorageById($op['type'])
        ->createItem($op['body'])
        ->execute();
    }
  }

  /**
   * @return string
   */
  public function serialize() {
    return serialize([
      $this->serializeSyncCore(),
      $this->type,
      $this->body,
      $this->downstream,
    ]);
  }

  /**
   * @param string $serialized
   */
  public function unserialize($serialized) {
    $data = unserialize($serialized);

    $this->unserializeSyncCore($data[0]);

    $this->type = $data[1];
    $this->body = $data[2];
    $this->downstream = $data[3];
  }

}
