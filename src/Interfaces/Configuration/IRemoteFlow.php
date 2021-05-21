<?php

namespace EdgeBox\SyncCore\Interfaces\Configuration;

interface IRemoteFlow
{
    /**
     * @return string|null
     */
    public function getConfig();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getSiteName();
}
