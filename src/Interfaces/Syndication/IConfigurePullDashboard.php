<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

interface IConfigurePullDashboard
{
    /**
     * @param string   $pool_id
     * @param string   $type
     * @param string   $bundle
     * @param string   $property
     * @param string[] $uuids
     *
     * @return $this
     */
    public function ifTaggedWith($pool_id, $type, $bundle, $property, $uuids);

    /**
     * @return array
     */
    public function getConfig();

    /**
     * @param string $pool_id
     * @param string $type
     * @param string $bundle
     *
     * @return $this
     */
    public function forEntityType($pool_id, $type, $bundle);

    /**
     * @param string $text
     *
     * @return $this
     */
    public function searchInTitle($text);

    /**
     * @param string $text
     *
     * @return $this
     */
    public function searchInPreview($text);

    /**
     * @param int|null $from
     * @param int|null $to
     *
     * @return $this
     */
    public function publishedBetween($from, $to);

    /**
     * @param bool     $order_by_title
     * @param bool     $order_ascending
     * @param int|null $page
     *
     * @return IPullDashboardSearchResult
     */
    public function runSearch($order_by_title = false, $order_ascending = false, $page = null);
}
