<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

interface IConfigurePullDashboard
{
    /**
     * @param string[] $uuids
     *
     * @return $this
     */
    public function ifTaggedWith(string $pool_id, string $type, string $bundle, string $property, array $uuids);

    /**
     * @return array
     */
    public function getConfig();

    /**
     * @return $this
     */
    public function forEntityType(string $pool_id, string $type, string $bundle);

    /**
     * @return $this
     */
    public function searchInTitle(string $text);

    /**
     * @return $this
     */
    public function searchInPreview(string $text);

    /**
     * @return $this
     */
    public function publishedBetween(?int $from, ?int $to);

    /**
     * @param bool     $order_by_title
     * @param bool     $order_ascending
     * @param null|int $page
     *
     * @return IPullDashboardSearchResult
     */
    public function runSearch($order_by_title = false, $order_ascending = false, $page = null);
}
