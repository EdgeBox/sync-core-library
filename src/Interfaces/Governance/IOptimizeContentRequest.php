<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

/**
 * The parsed inbound optimize-content trigger, and the two answers to it.
 */
interface IOptimizeContentRequest
{
    /**
     * @return string
     */
    public function getOptimizationId();

    /**
     * @return string the key the Sync Core files the page under, which names nothing a site can resolve on its own
     */
    public function getContentItemKey();

    /**
     * The page as a person finds it: its canonical URL, else the URL the crawl
     * fetched.
     *
     * It is the URL a site's own entity lookup is asked by, so a site that
     * offers no such lookup, and is therefore sent no page entity, still has
     * this to find the page by. Absent only for a page the Sync Core holds no
     * URL for.
     *
     * @return null|string
     */
    public function getPageUrl();

    /**
     * The thing the site already holds the page as.
     *
     * Absent when the Sync Core has no such reference for the page, which is
     * the case for every site that offers no lookup of its own entities by page
     * URL. A site that files what it receives per entity files nothing for such
     * a trigger and goes by the page URL instead.
     *
     * @return null|IPageEntityReference
     */
    public function getPageEntity();

    /**
     * @return null|string
     */
    public function getTargetLocale();

    /**
     * @return string[]
     */
    public function getOptimizationTypeKeys();

    /**
     * @return array{title?: string, url?: string, contentHealthPercent0To100?: int, contentPriority?: string, citedInAnswersLast30Days?: int}
     */
    public function getPage();

    /**
     * @return IIssueContext[]
     */
    public function getIssues();

    /**
     * The content the optimization recommends the site write, in the order it
     * names it.
     *
     * Empty when the optimization recommends none, and when the sender left the
     * list out to fit the size a site accepts.
     *
     * @return IRecommendationContext[]
     */
    public function getRecommendations();

    /**
     * Whether the sender dropped issues to fit the size a site accepts.
     *
     * It drops the lowest-priority issues first. The recommendations are not
     * counted here and nothing else reports on them: a recommendation the
     * sender left out, or a part it left off one, leaves this false.
     *
     * @return bool true when issues were dropped from this trigger
     */
    public function wasTruncated();

    /**
     * @return array the 202 acknowledgement body
     */
    public function accept();

    /**
     * @return array the 202 refusal body carrying the reason
     */
    public function refuse(string $reason);
}
