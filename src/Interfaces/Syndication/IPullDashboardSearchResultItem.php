<?php

namespace EdgeBox\SyncCore\Interfaces\Syndication;

interface IPullDashboardSearchResultItem
{
    public function extend(array $properties);

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getBundle();

    /**
     * @return array
     */
    public function toArray();
}
