<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\IRecommendationContext;

class RecommendationContext implements IRecommendationContext
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return (string) ($this->data['id'] ?? '');
    }

    public function getKind()
    {
        return (string) ($this->data['kind'] ?? '');
    }

    public function getLocale()
    {
        $locale = $this->data['locale'] ?? null;

        return new NamedReference(is_array($locale) ? $locale : []);
    }

    public function getTitle()
    {
        $title = $this->data['title'] ?? null;

        return new AuthoredText(is_array($title) ? $title : []);
    }

    public function getContent()
    {
        $content = $this->data['content'] ?? null;

        return is_array($content) ? new AuthoredText($content) : null;
    }

    public function getBrief()
    {
        $brief = $this->data['brief'] ?? null;

        return is_array($brief) ? new AuthoredText($brief) : null;
    }

    public function getTarget()
    {
        $target = $this->data['target'] ?? null;

        return is_array($target) ? new RecommendationTarget($target) : null;
    }

    public function getEvidence()
    {
        $evidence = $this->data['evidence'] ?? null;

        return is_array($evidence) ? new RecommendationEvidence($evidence) : null;
    }
}
