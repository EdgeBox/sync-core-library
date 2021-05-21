<?php

namespace EdgeBox\SyncCore\V1\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IFlowPullConfiguration;
use EdgeBox\SyncCore\V1\BatchOperation;
use EdgeBox\SyncCore\V1\Entity\Entity;
use EdgeBox\SyncCore\V1\Query\Condition\Condition;
use EdgeBox\SyncCore\V1\Query\Condition\DataCondition;
use EdgeBox\SyncCore\V1\Query\Condition\ParentCondition;

abstract class FlowPullConfigurationBase extends BatchOperation implements IFlowPullConfiguration
{
    protected $pull_condition = [];

    protected $index = 0;

    /**
     * @return $this
     */
    public function manually(bool $set)
    {
        $this->body['options']['modes'][$this->index]['is_manual'] = $set;

        return $this;
    }

    /**
     * @return $this
     */
    public function asDependency(bool $set)
    {
        $this->body['options']['modes'][$this->index]['is_dependent'] = $set;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function ifTaggedWith(string $property, array $allowed_entity_ids)
    {
        $this->pull_condition[] = DataCondition::in($property.'.*.'.Entity::UUID_KEY, $allowed_entity_ids);

        if (count($this->pull_condition) > 1) {
            $condition = ParentCondition::all($this->pull_condition);
        } else {
            /**
             * @var Condition $condition
             */
            $condition = $this->pull_condition[0];
        }

        $this->body['options']['modes'][$this->index]['condition'] = $condition->toArray();

        return $this;
    }

    /**
     * @return $this
     */
    public function pullDeletions(bool $set)
    {
        $this->body['options']['modes'][$this->index]['delete'] = $set;

        return $this;
    }
}
