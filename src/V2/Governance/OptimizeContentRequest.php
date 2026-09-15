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
        return array_values($this->body['optimizationTypeKeys'] ?? []);
    }

    public function getPage()
    {
        return $this->body['page'] ?? [];
    }

    public function getIssues()
    {
        $issues = [];
        foreach ($this->body['issues'] ?? [] as $issue) {
            $issues[] = new IssueContext($issue);
        }

        return $issues;
    }

    public function getRecommendations()
    {
        return array_values($this->body['recommendations'] ?? []);
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
