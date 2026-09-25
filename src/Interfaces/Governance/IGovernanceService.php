<?php

namespace EdgeBox\SyncCore\Interfaces\Governance;

interface IGovernanceService
{
    /**
     * Read an inbound optimize-content trigger.
     *
     * The body is the request's JSON decoded to arrays all the way down, the
     * way json_decode() returns it when it is asked for associative arrays.
     *
     * @return IOptimizeContentRequest
     */
    public function parseOptimizeContentRequest(array $query, array $body);

    /**
     * @return IPostExternalDraft
     */
    public function postExternalDraft(string $content_item_key, string $external_revision_id, string $rendered_html, ?ActingUser $as = null);

    /**
     * @return IContentItemSummary
     */
    public function getContentItemByKey(string $key, string $status, ?ActingUser $as = null);

    /**
     * @return ITaxonomyTermSummary
     */
    public function getTaxonomyTermByKey(string $key, string $status, ?ActingUser $as = null);
}
