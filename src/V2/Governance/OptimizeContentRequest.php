<?php

namespace EdgeBox\SyncCore\V2\Governance;

use EdgeBox\SyncCore\Interfaces\Governance\IOptimizeContentRequest;
use EdgeBox\SyncCore\Interfaces\Governance\IPageEntityReference;

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

    /**
     * The page as a person finds it: its canonical URL, else the URL the crawl
     * fetched.
     *
     * It is the URL a site's own entity lookup is asked by, so a site that
     * offers no such lookup, and is therefore sent no page entity, still has
     * this to find the page by. Absent only for a page the Sync Core holds no
     * URL for.
     *
     * Declared on this class and not on IOptimizeContentRequest, so a class
     * that implements IOptimizeContentRequest stays compatible.
     *
     * @return null|string
     */
    public function getPageUrl()
    {
        return $this->getPage()['url'] ?? null;
    }

    /**
     * The thing the site already holds the page as.
     *
     * Absent when the Sync Core has no such reference for the page, which is
     * the case for every site that offers no lookup of its own entities by page
     * URL. A site that files what it receives per entity files nothing for such
     * a trigger and goes by the page URL instead.
     *
     * Declared on this class and not on IOptimizeContentRequest, so a class
     * that implements IOptimizeContentRequest stays compatible.
     *
     * @return null|IPageEntityReference
     */
    public function getPageEntity()
    {
        $entity = $this->body['pageEntity'] ?? null;

        return is_array($entity) ? new PageEntityReference($entity) : null;
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
