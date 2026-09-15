<?php

namespace EdgeBox\SyncCore\Interfaces\Aim;

interface IAimService
{
    /**
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
