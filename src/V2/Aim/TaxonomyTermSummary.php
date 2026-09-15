<?php

namespace EdgeBox\SyncCore\V2\Aim;

use EdgeBox\SyncCore\Interfaces\Aim\ITaxonomyTermSummary;
use EdgeBox\SyncCore\V2\Raw\Model\TaxonomyTermEntity;

class TaxonomyTermSummary implements ITaxonomyTermSummary
{
    /**
     * @var TaxonomyTermEntity
     */
    protected $entity;

    public function __construct(TaxonomyTermEntity $entity)
    {
        $this->entity = $entity;
    }

    public function getKey()
    {
        return (string) $this->entity->getKey();
    }

    public function getName()
    {
        return $this->entity->getName();
    }

    public function getPriority()
    {
        return $this->entity->getPriority();
    }

    public function getInstruction()
    {
        return $this->entity->getInstruction();
    }
}
