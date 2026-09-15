<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

/**
 * The subset of a content item a site reads by key.
 */
interface IContentItemSummary
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @return null|string the page's title
     */
    public function getTitle();

    /**
     * @return null|float 0..1
     */
    public function getHealthScore();

    /**
     * @return null|string
     */
    public function getContentPriority();

    /**
     * @return int
     */
    public function getOpenIssueCount();

    /**
     * @return string[] the issue-type tag keys
     */
    public function getIssueTypeKeys();

    /**
     * @return null|int epoch milliseconds
     */
    public function getLastCrawledAt();
}
