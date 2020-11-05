<?php

namespace EdgeBox\SyncCore\V1\Configuration;

use Drupal\cms_content_sync\SyncCore\Interfaces\Configuration\IFlowPullConfiguration;
use Drupal\cms_content_sync\SyncCore\V1\BatchOperation;
use Drupal\cms_content_sync\SyncCore\V1\Entity\Entity;
use Drupal\cms_content_sync\SyncCore\V1\Query\Condition\DataCondition;
use Drupal\cms_content_sync\SyncCore\V1\Query\Condition\ParentCondition;

/**
 *
 */
abstract class FlowPullConfigurationBase extends BatchOperation implements IFlowPullConfiguration {
  protected $pull_condition = [];
  protected $index = 0;

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function manually($set) {
    $this->body['options']['modes'][$this->index]['is_manual'] = $set;
    return $this;
  }

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function asDependency($set) {
    $this->body['options']['modes'][$this->index]['is_dependent'] = $set;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function ifTaggedWith($property, $allowed_entity_ids) {
    $this->pull_condition[] = DataCondition::in($property . '.*.' . Entity::UUID_KEY, $allowed_entity_ids);

    /**
     * @var \Drupal\cms_content_sync\SyncCore\V1\Query\Condition\Condition $condition
     */

    if (count($this->pull_condition) > 1) {
      $condition = ParentCondition::all($this->pull_condition);
    }
    else {
      $condition = $this->pull_condition[0];
    }

    $this->body['options']['modes'][$this->index]['condition'] = $condition->toArray();
    return $this;
  }

  /**
   * @param bool $set
   *
   * @return $this
   */
  public function pullDeletions($set) {
    $this->body['options']['modes'][$this->index]['delete'] = $set;
    return $this;
  }

}
