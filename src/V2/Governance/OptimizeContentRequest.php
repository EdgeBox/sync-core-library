<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\IOptimizeContentRequest;

/**
 * An inbound optimize-content trigger, parsed from the request's query and body.
 */
class OptimizeContentRequest implements IOptimizeContentRequest
{
    /**
     * @var array
     */
    protected $query;

    /**
     * @var array
     */
    protected $body;

    public function __construct(array $query, array $body)
    {
        $this->query = $query;
        $this->body = $body;
    }

    public function getOptimizationId()
    {
        return (string) ($this->query['optimizationId'] ?? $this->body['optimizationId'] ?? '');
    }

    public function getContentItemKey()
    {
        return (string) ($this->body['contentItemKey'] ?? '');
    }

    public function getTargetLocale()
    {
        return $this->body['targetLocale'] ?? null;
    }

    public function getOptimizationTypeKeys()
    {
        $keys = $this->body['optimizationTypeKeys'] ?? null;

        return is_array($keys) ? array_values($keys) : [];
    }

    public function getPage()
    {
        $page = $this->body['page'] ?? null;

        return is_array($page) ? $page : [];
    }

    public function getIssues()
    {
        $issues = [];
        $list = $this->body['issues'] ?? null;
        foreach (is_array($list) ? $list : [] as $issue) {
            if (is_array($issue)) {
                $issues[] = new IssueContext($issue);
            }
        }

        return $issues;
    }

    public function getRecommendations()
    {
        $recommendations = [];
        $list = $this->body['recommendations'] ?? null;
        foreach (is_array($list) ? $list : [] as $recommendation) {
            if (is_array($recommendation)) {
                $recommendations[] = new RecommendationContext($recommendation);
            }
        }

        return $recommendations;
    }

    public function wasTruncated()
    {
        return (bool) ($this->body['truncated'] ?? false);
    }

    public function accept()
    {
        return ['accepted' => true];
    }

    public function refuse(string $reason)
    {
        return ['accepted' => false, 'reason' => $reason];
    }
}
