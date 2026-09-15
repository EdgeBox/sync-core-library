<?php

namespace EdgeBox\SyncCore\V2\Aim;

use EdgeBox\SyncCore\Interfaces\Aim\IContentItemSummary;
use EdgeBox\SyncCore\V2\Raw\Model\ContentItemEntity;

class ContentItemSummary implements IContentItemSummary
{
    /**
     * @var ContentItemEntity
     */
    protected $entity;

    public function __construct(ContentItemEntity $entity)
    {
        $this->entity = $entity;
    }

    public function getKey()
    {
        return (string) $this->entity->getKey();
    }

    public function getTitle()
    {
        return $this->entity->getName();
    }

    public function getHealthScore()
    {
        return $this->entity->getHealthScore();
    }

    public function getContentPriority()
    {
        /** @var mixed $priority */
        $priority = $this->entity->getContentPriority();

        return null === $priority ? null : (string) $priority;
    }

    public function getOpenIssueCount()
    {
        $issues = $this->entity->getIssues();

        return $issues ? (int) $issues->getOpenCount() : 0;
    }

    public function getIssueTypeKeys()
    {
        $issues = $this->entity->getIssues();
        if (!$issues) {
            return [];
        }

        $keys = [];
        foreach ($issues->getTypes() ?? [] as $type) {
            $keys[] = (string) $type->getKey();
        }

        return $keys;
    }

    public function getLastCrawledAt()
    {
        $crawled = $this->entity->getLastCrawledAt();

        return null === $crawled ? null : (int) $crawled;
    }
}
