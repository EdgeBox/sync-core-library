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
     * @return string
     */
    public function getContentItemKey();

    /**
     * @return null|string
     */
    public function getTargetLocale();

    /**
     * @return string[]
     */
    public function getOptimizationTypeKeys();

    /**
     * @return array{title?: string, contentHealthPercent0To100?: int, contentPriority?: string, citedInAnswersLast30Days: int}
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
